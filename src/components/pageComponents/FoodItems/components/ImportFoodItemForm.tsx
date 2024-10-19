import React from "react";
import Papa from "papaparse";
import { ItemData } from "../../../../types/ItemsForm";
import useGetFoodItemService from "../hooks/useGetFoodItemService";

interface ImportFoodItemFormProps {
  handleClose: () => void;
  fetchItemsData: () => void;
  itemsData: ItemData[];
}

export const ImportFoodItemForm: React.FC<ImportFoodItemFormProps> = ({
  handleClose,
  fetchItemsData,
  itemsData,
}) => {
  const { setItems } = useGetFoodItemService();

  // Handle CSV upload
  const handleCsvUpload = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (file) {
      Papa.parse(file, {
        complete: async (results: any) => {
          const data = results.data.slice(1); // Exclude header row
          const parsedItems: ItemData[] = data.map(
            (row: any, index: number) => ({
              id: itemsData.length + index + 1,
              name: row[0],
              category: row[1],
              iddsi_level: row[2],
            })
          );

          try {
            for (const item of parsedItems) {
              await setItems(item.name, item.category, item.iddsi_level);
            }
            fetchItemsData(); // Refresh item list after upload
            alert("Items uploaded and saved successfully!");
          } catch (error) {
            console.error("Error uploading CSV:", error);
            //alert("Error uploading CSV.");
            fetchItemsData(); // Refresh item list after upload
          }
          handleClose(); // Close modal after upload
        },
        header: false,
      });
    }
  };

  return (
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
              <button
                type="button"
                className="btn btn-secondary"
                onClick={handleClose}
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
      <div className="modal-backdrop fade show"></div>
    </>
  );
};
