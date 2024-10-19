import React from "react";
import DataTable from "react-data-table-component";
import { ItemData } from "../../../../types/ItemsForm"; // Import your types

interface FoodItemlistProps {
  itemsData?: ItemData[];
  handleEdit: (item: ItemData) => void;
  handleDelete: (item: ItemData) => void;
}
const FoodItemlist: React.FC<FoodItemlistProps> = ({
  itemsData = [],
  handleEdit,
  handleDelete,
}) => {
  return (
    <DataTable
      title="Food Items List"
      columns={[
        {
          name: "ID",
          selector: (row: ItemData) => row.id,
          sortable: true,
        },
        {
          name: "Name",
          selector: (row: ItemData) => row.name,
          sortable: true,
        },
        {
          name: "Category",
          selector: (row: ItemData) => row.category,
          sortable: true,
        },
        {
          name: "IDDSI Level",
          selector: (row: ItemData) => row.iddsi_level,
          sortable: true,
        },
        {
          name: "Actions",
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
                onClick={() => handleDelete(row)}
              >
                Delete
              </button>
            </>
          ),
          ignoreRowClick: true, // Keep this if you want to prevent row clicks from firing
        },
      ]}
      data={itemsData}
      pagination
      selectableRows
      highlightOnHover
    />
  );
};

export default FoodItemlist; // Ensure this export is present
