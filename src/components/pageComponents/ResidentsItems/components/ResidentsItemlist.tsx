import React from "react";
import DataTable from "react-data-table-component";
import { ResidentsData } from "../../../../types/ResidentsForm"; // Import your types

interface ResidentsItemListProps {
  ResidentsData?: ResidentsData[];
  handleEdit: (item: ResidentsData) => void;
  handleDelete: (item: ResidentsData) => void;
}
const ResidentsItemlist: React.FC<ResidentsItemListProps> = ({
  ResidentsData = [],
  handleEdit,
  handleDelete,
}) => {
  return (
    <DataTable
      title="Residents List"
      columns={[
        {
          name: "ID",
          selector: (row: ResidentsData) => row.id,
          sortable: true,
        },
        {
          name: "Name",
          selector: (row: ResidentsData) => row.name,
          sortable: true,
        },
        {
          name: "Category",
          selector: (row: ResidentsData) => row.category,
          sortable: true,
        },
        {
          name: "IDDSI Level",
          selector: (row: ResidentsData) => row.iddsi_level,
          sortable: true,
        },
        {
          name: "Actions",
          cell: (row: ResidentsData) => (
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
                onClick={() => handleDelete(row)}
              >
                Delete
              </button>
            </>
          ),
          ignoreRowClick: true, // Keep this if you want to prevent row clicks from firing
        },
      ]}
      data={ResidentsData}
      pagination
      selectableRows
      highlightOnHover
    />
  );
};

export default ResidentsItemlist; // Ensure this export is present
