import React, { useState, useEffect } from "react";
import { useForm } from "react-hook-form";
import { ItemData, ItemsForm } from "../../../types/ItemsForm"; // Import your types
import FoodItemlist from "./components/FoodItemlist";
import useGetFoodItemService from "./hooks/useGetFoodItemService";
import { FoodItemToolBar } from "./components/FoodItemToolBar";
import { AddEditFoodItemForm } from "./components/AddEditFoodItemForm";
import { ImportFoodItemForm } from "./components/ImportFoodItemForm";

const FoodItems: React.FC = () => {
  const { setValue, reset } = useForm<ItemsForm>();
  const [itemsData, setItemsData] = useState<ItemData[]>([]);
  const [showModal, setShowModal] = useState(false);
  const [editMode, setEditMode] = useState(false);
  const [currentItem, setCurrentItem] = useState<ItemData | null>(null);
  const [showCsvModal, setShowCsvModal] = useState(false);
  const [loading, setLoading] = useState(false);

  const { getItems, deleteItems } = useGetFoodItemService();

  // Fetch data dynamically when the component mounts
  useEffect(() => {
    fetchItemsData();
  }, []);

  // Fetch items data from the backend
  const fetchItemsData = async () => {
    setLoading(true); // Show loading state
    try {
      const response = await getItems(); // Adjust based on your service
      const items = response.data || response;
      const formattedItems = items.map((item: any) => ({
        id: item.id,
        name: item.name,
        category: item.category,
        iddsi_level: item.iddsi_level,
      }));
      setItemsData(formattedItems);
    } catch (error) {
      console.error("Error fetching items data:", error);
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
    setValue("name", item.name);
    setValue("category", item.category);
    setValue("iddsi_level", item.iddsi_level);
  };

  // Handle delete item
  const handleDelete = async (data: ItemData) => {
    const id = data?.id;

    try {
      await deleteItems(id);
      setItemsData((prevItems) => prevItems.filter((item) => item.id !== id)); // Update state
      alert(`Item with ID ${id} deleted successfully.`);
    } catch (error) {
      console.error(`Error deleting item with ID ${id}:`, error);
      alert(`Error deleting item with ID ${id}`);
    }
  };

  return (
    <div className="container mt-5">
      <div className="row justify-content-center">
        <div className="col-md-12">
          <h2 className="text-center">Food Items</h2>

          <FoodItemToolBar
            handleShow={handleShow}
            setShowCsvModal={setShowCsvModal}
          />

          {/* Modal for Add/Edit Item */}
          {showModal && (
            <AddEditFoodItemForm
              editMode={editMode}
              handleClose={handleClose}
              currentItem={currentItem as ItemData}
              setItemsData={setItemsData}
              itemsData={itemsData}
            />
          )}

          {/* CSV Upload Modal */}
          {showCsvModal && (
            <ImportFoodItemForm
              handleClose={handleClose}
              fetchItemsData={fetchItemsData}
              itemsData={itemsData}
            />
          )}

          {/* Display the list of items */}
          <FoodItemlist
            itemsData={itemsData as ItemData[]}
            handleEdit={handleEdit}
            handleDelete={handleDelete}
          />

          {/* Show loading state if data is being fetched */}
          {loading && <div>Loading items...</div>}
        </div>
      </div>
    </div>
  );
};

export default FoodItems;
