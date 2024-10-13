import React, { useState, useEffect } from 'react';
import DataTable from 'react-data-table-component';
import { useForm } from 'react-hook-form';
import Commonservice from '../Commonservices';
import Papa from 'papaparse';

interface ItemData {
  id: number;
  name: string;
  category: string;
  iddsi_level: string;
}

interface ItemsForm {
  id: number;
  name: string;
  category: string;
  iddsi_level: string;
}

const ReadItems: React.FC = () => {
  const { register, handleSubmit, setValue, formState: { errors } } = useForm<ItemsForm>();
  const [itemsData, setItemsData] = useState<ItemData[]>([]);
  const [showModal, setShowModal] = useState(false);
  const [editMode, setEditMode] = useState(false);
  const [currentItem, setCurrentItem] = useState<ItemData | null>(null);
  const [showCsvModal, setShowCsvModal] = useState(false);

  // Fetch data dynamically when component mounts
  useEffect(() => {
    fetchItemsData();
  }, []);

  const fetchItemsData = async () => {
    try {
      const response = await Commonservice.getItems();
      const items = response.data || response;
      // Map and format the response to match your state structure
      const formattedItems = items.map((item: any) => ({
        id: item.id,
        name: item.name,
        category: item.category,
        iddsi_level: item.iddsi_level,
      }));
      setItemsData(formattedItems);
    } catch (error) {
      console.error('Error fetching items data:', error);
    }
  };

  const handleShow = () => {
    setShowModal(true);
    setEditMode(false);
  };

  const handleClose = () => {
    setShowModal(false);
    setCurrentItem(null);
  };

  // CSV Upload Handler
  const handleCsvUpload = async (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (file) {
      Papa.parse(file, {
        complete: async function (results: any) {
          const data = results.data;
          const parsedItems: ItemData[] = data.slice(1).map((row: any, index: number) => ({
            id: itemsData.length + index + 1,
            name: row[0],
            category: row[1],
            iddsi_level: row[2],
          }));

          // Upload each parsed item to the backend
          for (const item of parsedItems) {
            try {
              await Commonservice.setItems(item.name, item.category, item.iddsi_level);
            } catch (error) {
              console.error('Error uploading item:', item, error);
            }
          }

          fetchItemsData();
          alert("Items uploaded and saved successfully!");
        },
        header: false,
      });
    }
    handleClose();
  };

  const handleShowCsvModal = () => {
    setShowCsvModal(true);
  };

  const onSubmit = async (data: ItemsForm) => {
    if (editMode && currentItem) {
      // Logic for updating the item
      try {
        await Commonservice.updateItems(currentItem.id, data.name, data.category, data.iddsi_level); // Update item in the backend
        const updatedItems = itemsData.map(item =>
          item.id === currentItem.id ? { ...item, name: data.name, category: data.category, iddsi_level: data.iddsi_level } : item
        );
        setItemsData(updatedItems); // Update the state with the updated item
        alert('Item updated successfully!');
      } catch (error) {
        console.error('Error updating item:', error);
      }
    } else {
      // Logic for creating a new item
      try {
        await Commonservice.setItems(data.name, data.category, data.iddsi_level);
        alert('Item inserted successfully!');

        const newItem: ItemData = {
          id: itemsData.length + 1, // Replace this with the id from backend if available
          name: data.name,
          category: data.category,
          iddsi_level: data.iddsi_level,
        };

        setItemsData([...itemsData, newItem]);
      } catch (error) {
        console.error('Error during item insertion:', error);
      }
    }
    handleClose();
  };

  // Edit item handler
  const handleEdit = (item: ItemData) => {
    setEditMode(true);
    setCurrentItem(item);
    setShowModal(true);
    setValue('id', item.id);
    setValue('name', item.name);
    setValue('category', item.category);
    setValue('iddsi_level', item.iddsi_level);
  };

  // Delete item handler
  const handleDelete = async (id: number) => {
    try {
      await Commonservice.DeleteItems(id); // Call to delete the item from the backend
      const filteredItems = itemsData.filter(item => item.id !== id);
      setItemsData(filteredItems); // Update the state by removing the deleted item
      alert(`Item with ID ${id} deleted successfully.`);
    } catch (error) {
      console.error(`Error deleting item with ID ${id}:`, error);
      alert(`Error deleting item with ID ${id}`);
    }
  };
  const columns = [
    {
      name: 'ID',
      selector: (row: ItemData) => row.id,
      sortable: true,
    },
    {
      name: 'Name',
      selector: (row: ItemData) => row.name,
      sortable: true,
    },
    {
      name: 'Category',
      selector: (row: ItemData) => row.category,
      sortable: true,
    },
    {
      name: 'IDDSI Level',
      selector: (row: ItemData) => row.iddsi_level,
      sortable: true,
    },
    {
      name: 'Actions',
      cell: (row: ItemData) => (
        <>
          <button
            type="button"
            className="btn btn-primary mr-2"
            onClick={() => handleEdit(row)}
          >
            Edit
          </button>
          <button
            type="button"
            className="btn btn-danger"
            onClick={() => handleDelete(row.id)}
          >
            Delete
          </button>
        </>
      ),
      ignoreRowClick: true, // Keep this if you want to prevent row clicks from firing
    },
  ];
  

  return (
    <div className="container mt-5">
      <div className="row justify-content-center">
        <div className="col-md-12">
          <h2 className="text-center">Items</h2>
          <div className="col-md-12 align-right">
            <button type="button" className="btn btn-primary m-3 w100" onClick={handleShow}>
              Add Item
            </button>
            <button type="button" className="btn btn-primary m-3 w100" onClick={handleShowCsvModal}>
              Add Item bulk using CSV
            </button>

            <a href="/residents" className="btn-primary m-3 w100" >
              Add Residents
            </a>
          </div>

          {/* Modal */}
          {showModal && (
            <>
              <div className="modal show d-block" role="dialog">
                <div className="modal-dialog" role="document">
                  <div className="modal-content">
                    <div className="modal-header">
                      <h5 className="modal-title">
                        {editMode ? 'Edit Item' : 'Add New Item'}
                      </h5>
                      <button type="button" className="close" aria-label="Close" onClick={handleClose}>
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div className="modal-body">
                      <form onSubmit={handleSubmit(onSubmit)}>
                      <input {...register('id')} type="hidden" id="id"/>
                        <div className="form-group mb-3">
                          <label htmlFor="name">Name</label>
                          <input
                            {...register('name', { required: true })}
                            type="text"
                            className="form-control"
                            id="name"
                            placeholder="Enter item name"
                          />
                          {errors.name && <span className="text-danger">This field is required</span>}
                        </div>

                        {/* Category Select Box */}
                        <div className="form-group mb-3">
                          <label htmlFor="category">Category</label>
                          <select
                            {...register('category', { required: true })}
                            className="form-control"
                            id="category"
                          >
                            <option value="">Select Category</option>
                            <option value="chicken">chicken</option>
                            <option value="pork">pork</option>
                            <option value="fish">fish</option>
                            <option value="veg">veg</option>
                          </select>
                          {errors.category && <span className="text-danger">Category is required</span>}
                        </div>

                        {/* IDDSI Level Select Box */}
                        <div className="form-group mb-3">
                          <label htmlFor="iddsi_level">IDDSI Level</label>
                          <select
                            {...register('iddsi_level', { required: true })}
                            className="form-control"
                            id="iddsi_level"
                          >
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
                <div className="modal-dialog" role="document">
                  <div className="modal-content">
                    <div className="modal-header">
                      <h5 className="modal-title">Upload CSV</h5>
                      <button type="button" className="close" aria-label="Close" onClick={handleClose}>
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div className="modal-body">
                      <input
                        type="file"
                        className="form-control mb-3"
                        id="csvFile"
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

          {/* DataTable */}
          <DataTable
            title="Items List"
            columns={columns}
            data={itemsData}
            pagination
            selectableRows
            highlightOnHover
          />
        </div>
      </div>
    </div>
  );
};

export default ReadItems;
