import React from "react";
import Papa from "papaparse";
import { ResidentsData } from "../../../../types/ResidentsForm";
import useGetResidentsItemService from "../hooks/useGetResidentsItemService";

interface ImportResidentsItemFormProps {
  handleClose: () => void;
  fetchResidentsData: () => void;
  residentsData: ResidentsData[];
}

export const ImportResidentsItemForm: React.FC<ImportResidentsItemFormProps> = ({
  handleClose,
  fetchResidentsData,
  residentsData,
}) => {
  const { setResidents } = useGetResidentsItemService();

  // Handle CSV upload
  const handleCsvUpload = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (file) {
      Papa.parse(file, {
        complete: async (results: any) => {
          const data = results.data.slice(1); // Exclude header row
          const parsedItems: ResidentsData[] = data.map(
            (row: any, index: number) => ({
              id: residentsData.length + index + 1,
              name: row[0],
              category: row[1],
              iddsi_level: row[2],
            })
          );

          try {
            for (const item of parsedItems) {
              await setResidents(item.name, item.category, item.iddsi_level);
            }
            fetchResidentsData(); // Refresh item list after upload
            alert("Items uploaded and saved successfully!");
          } catch (error) {
            console.error("Error uploading CSV:", error);
            fetchResidentsData();
            //alert("Error uploading CSV.");
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
