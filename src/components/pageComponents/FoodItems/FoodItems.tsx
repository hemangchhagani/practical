import React, { useState, useEffect } from 'react';
import { useForm } from 'react-hook-form';
import Papa from 'papaparse';
import ItemService from './hooks/useGetFoodItemService'; // Adjust import as necessary
import { ItemData, ItemsForm } from '../../../types/ItemsForm'; // Import your types
import FoodItemlist from './components/FoodItemlist';

const FoodItems: React.FC = () => {
  const { register, handleSubmit, setValue, formState: { errors }, reset } = useForm<ItemsForm>();
  const [itemsData, setItemsData] = useState<ItemData[]>([]);
  const [showModal, setShowModal] = useState(false);
  const [editMode, setEditMode] = useState(false);
  const [currentItem, setCurrentItem] = useState<ItemData | null>(null);
  const [showCsvModal, setShowCsvModal] = useState(false);
  const [loading, setLoading] = useState(false);

  // Fetch data dynamically when the component mounts
  useEffect(() => {
    fetchItemsData();
  }, []);

  // Fetch items data from the backend
  const fetchItemsData = async () => {
    setLoading(true); // Show loading state
    try {
      const response = await ItemService.getItems(); // Adjust based on your service
      const items = response.data || response;
      const formattedItems = items.map((item: any) => ({
        id: item.id,
        name: item.name,
        category: item.category,
        iddsi_level: item.iddsi_level,
      }));
      setItemsData(formattedItems);
    } catch (error) {
      console.error('Error fetching items data:', error);
    } finally {
      setLoading(false); // Hide loading state
    }
  };

  // Handle opening the form modal
  const handleShow = () => {
    setShowModal(true);
    setEditMode(false);
    reset(); // Clear form fields when adding a new item
  };

  // Handle closing the modals (both CSV and form modals)
  const handleClose = () => {
    setShowModal(false);
    setShowCsvModal(false);
    setCurrentItem(null);
  };

  // Handle item edit
  const handleEdit = (item: ItemData) => {
    setEditMode(true);
    setCurrentItem(item);
    setShowModal(true);
    setValue('name', item.name);
    setValue('category', item.category);
    setValue('iddsi_level', item.iddsi_level);
  };

  // Handle delete item
  const handleDelete = async (id: number) => {
    try {
      await ItemService.deleteItems(id);
      setItemsData((prevItems) => prevItems.filter(item => item.id !== id)); // Update state
      alert(`Item with ID ${id} deleted successfully.`);
    } catch (error) {
      console.error(`Error deleting item with ID ${id}:`, error);
      alert(`Error deleting item with ID ${id}`);
    }
  };

  // Handle form submission
  const onSubmit = async (data: ItemsForm) => {
    try {
      if (editMode && currentItem) {
        // Edit existing item
        await ItemService.updateItems(currentItem.id, data.name, data.category, data.iddsi_level);
        setItemsData((prevItems) =>
          prevItems.map((item) =>
            item.id === currentItem.id
              ? { ...item, name: data.name, category: data.category, iddsi_level: data.iddsi_level }
              : item
          )
        );
        alert('Item updated successfully!');
      } else {
        // Add new item
        const response = await ItemService.setItems(data.name, data.category, data.iddsi_level);
        setItemsData((prevItems) => [...prevItems, response.data]);
        alert('New item added successfully!');
      }
      handleClose(); // Close modal after success
    } catch (error) {
      console.error('Error submitting form:', error);
      alert('Error saving item.');
    }
  };

  // Handle CSV upload
  const handleCsvUpload = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (file) {
      Papa.parse(file, {
        complete: async (results: any) => {
          const data = results.data.slice(1); // Exclude header row
          const parsedItems: ItemData[] = data.map((row: any, index: number) => ({
            id: itemsData.length + index + 1,
            name: row[0],
            category: row[1],
            iddsi_level: row[2],
          }));

          try {
            for (const item of parsedItems) {
              await ItemService.setItems(item.name, item.category, item.iddsi_level);
            }
            fetchItemsData(); // Refresh item list after upload
            alert("Items uploaded and saved successfully!");
          } catch (error) {
            console.error('Error uploading CSV:', error);
            alert('Error uploading CSV.');
          }
          handleClose(); // Close modal after upload
        },
        header: false,
      });
    }
  };

  return (
    <div className="container mt-5">
      <div className="row justify-content-center">
        <div className="col-md-12">
          <h2 className="text-center">Items</h2>
          <div className="col-md-12 align-right">
            <button type="button" className="btn btn-primary m-3" onClick={handleShow}>
              Add Item
            </button>
            <button type="button" className="btn btn-primary m-3" onClick={() => setShowCsvModal(true)}>
              Add Items (CSV)
            </button>
            <a href="/residents" className="btn btn-primary m-3">Add Residents</a>
          </div>

          {/* Modal for Add/Edit Item */}
          {showModal && (
            <>
              <div className="modal show d-block" role="dialog">
                <div className="modal-dialog">
                  <div className="modal-content">
                    <div className="modal-header">
                      <h5 className="modal-title">{editMode ? 'Edit Item' : 'Add New Item'}</h5>
                      <button type="button" className="close" onClick={handleClose}>
                        <span>&times;</span>
                      </button>
                    </div>
                    <div className="modal-body">
                      <form onSubmit={handleSubmit(onSubmit)}>
                        <div className="form-group mb-3">
                          <label htmlFor="name">Name</label>
                          <input
                            {...register('name', { required: true })}
                            type="text"
                            className="form-control"
                            placeholder="Enter item name"
                          />
                          {errors.name && <span className="text-danger">This field is required</span>}
                        </div>
                        <div className="form-group mb-3">
                          <label htmlFor="category">Category</label>
                          <select {...register('category', { required: true })} className="form-control">
                            <option value="">Select Category</option>
                            <option value="chicken">Chicken</option>
                            <option value="pork">Pork</option>
                            <option value="fish">Fish</option>
                            <option value="veg">Veg</option>
                          </select>
                          {errors.category && <span className="text-danger">Category is required</span>}
                        </div>
                        <div className="form-group mb-3">
                          <label htmlFor="iddsi_level">IDDSI Level</label>
                          <select {...register('iddsi_level', { required: true })} className="form-control">
                            <option value="">Select IDDSI Level</option>
                            <option value="Thin">Thin</option>
                            <option value="Slightly Thick">Slightly Thick</option>
                            <option value="Mildly Thick">Mildly Thick</option>
                            <option value="Moderately Thick">Moderately Thick</option>
                            <option value="Extremely Thick">Extremely Thick</option>
                            <option value="Regular">Regular</option>
                            <option value="Easy to Chew">Easy to Chew</option>
                            <option value="Soft & Bite-Sized">Soft & Bite-Sized</option>
                            <option value="Mince & Moist">Mince & Moist</option>
                            <option value="Pureed">Pureed</option>
                            <option value="Liquidised">Liquidised</option>
                          </select>
                          {errors.iddsi_level && <span className="text-danger">IDDSI Level is required</span>}
                        </div>
                        <button type="submit" className="btn btn-primary w-100">
                          {editMode ? 'Update Item' : 'Add Item'}
                        </button>
                      </form>
                    </div>
                    <div className="modal-footer">
                      <button type="button" className="btn btn-secondary" onClick={handleClose}>
                        Close
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div className="modal-backdrop fade show"></div>
            </>
          )}

          {/* CSV Upload Modal */}
          {showCsvModal && (
            <>
              <div className="modal show d-block" role="dialog">
                <div className="modal-dialog">
                  <div className="modal-content">
                    <div className="modal-header">
                      <h5 className="modal-title">Upload CSV</h5>
                      <button type="button" className="close" onClick={handleClose}>
                        <span>&times;</span>
                      </button>
                    </div>
                    <div className="modal-body">
                      <input
                        type="file"
                        className="form-control mb-3"
                        accept=".csv"
                        onChange={handleCsvUpload}
                      />
                    </div>
                    <div className="modal-footer">
                      <button type="button" className="btn btn-secondary" onClick={handleClose}>
                        Close
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div className="modal-backdrop fade show"></div>
            </>
          )}

          {/* Display the list of items */}
          <FoodItemlist itemsData={itemsData} />

          {/* Show loading state if data is being fetched */}
          {loading && <div>Loading items...</div>}
        </div>
      </div>
    </div>
  );
};

export default FoodItems;
