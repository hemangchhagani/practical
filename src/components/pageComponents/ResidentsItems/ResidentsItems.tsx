import React, { useState, useEffect } from "react";
import { useForm } from "react-hook-form";
import { ResidentsData, ResidentsForm } from "../../../types/ResidentsForm"; // Import your types
import ResidentsItemList from "./components/ResidentsItemlist"; // Ensure correct import
import useGetResidentsItemService from "./hooks/useGetResidentsItemService";
import { ResidentsItemToolBar } from "./components/ResidentsItemToolBar";
import { AddEditResidentsItemForm } from "./components/AddEditResidentsItemForm";
import { ImportResidentsItemForm } from "./components/ImportResidentsItemForm";

const ResidentsItems: React.FC = () => {
  const { setValue, reset } = useForm<ResidentsForm>();
  const [residentsData, setResidentsData] = useState<ResidentsData[]>([]); // Renamed to avoid conflict with the type name
  const [showModal, setShowModal] = useState(false);
  const [editMode, setEditMode] = useState(false);
  const [currentResidents, setCurrentResidents] = useState<ResidentsData | null>(null); // Renamed to 'setCurrentResidents'
  const [showCsvModal, setShowCsvModal] = useState(false);
  const [loading, setLoading] = useState(false);

  const { getResidents, deleteResidents } = useGetResidentsItemService();

  // Fetch data dynamically when the component mounts
  useEffect(() => {
    fetchResidentsData();
  }, []);

  // Fetch items data from the backend
  // const fetchResidentsData = async () => {
  //   setLoading(true); // Show loading state
  //   try {
  //     const response = await getResidents(); // Adjust based on your service

  //     const items = response.data || response;
  //     console.log(items);
  //     const formattedItems = items.map((item: any) => ({
  //       id: item.id,
  //       name: item.name,
  //       category: item.category,
  //       iddsi_level: item.iddsi_level,
  //     }));
  //     setResidentsData(formattedItems);
  //   } catch (error) {
  //     console.error("Error fetching Residents data:", error);
  //   } finally {
  //     setLoading(false); // Hide loading state
  //   }
  // };

  const fetchResidentsData = async () => {
  setLoading(true); // Show loading state
  try {
    const response = await getResidents(); // Adjust based on your service
    // Directly access the `data` property to avoid issues
    const items = response?.data?.data || [];
    console.log(items);
    
    // Ensure that the items array is properly mapped
    const formattedItems = items.map((item: any) => ({
      id: item.id || '',
      name: item.name || '',
      category: item.category || '',
      iddsi_level: item.iddsi_level || '',
    }));
    setResidentsData(formattedItems);
  } catch (error) {
    console.error("Error fetching Residents data:", error);
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
    setCurrentResidents(null); // Reset current item after closing
  };

  // Handle item edit
  const handleEdit = (item: ResidentsData) => {
    setEditMode(true);
    setCurrentResidents(item);
    setShowModal(true);
    // Populate the form with the selected item data
    setValue("name", item.name);
    setValue("category", item.category);
    setValue("iddsi_level", item.iddsi_level);
  };

  // Handle delete item
  const handleDelete = async (data: ResidentsData) => {
    const id = data?.id;

    try {
      await deleteResidents(id);
      setResidentsData((prevItems) => prevItems.filter((item) => item.id !== id)); // Update state after deletion
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
          <h2 className="text-center">Residents Items</h2>

          {/* Toolbar for adding new items and importing CSV */}
          <ResidentsItemToolBar
            handleShow={handleShow}
            setShowCsvModal={setShowCsvModal}
          />

          {/* Modal for Add/Edit Item */}
          {showModal && (
            <AddEditResidentsItemForm
              editMode={editMode}
              handleClose={handleClose}
              currenResidents={currentResidents as ResidentsData} // Ensure type is passed correctly
              setResidentsData={setResidentsData}
              ResidentsData={residentsData}
            />
          )}

          {/* CSV Upload Modal */}
          {showCsvModal && (
            <ImportResidentsItemForm
              handleClose={handleClose}
              fetchResidentsData={fetchResidentsData}
              residentsData={residentsData}
            />
          )}

          {/* Display the list of items */}
          <ResidentsItemList
            ResidentsData={residentsData}
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

export default ResidentsItems;
