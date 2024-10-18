import React, { useState, useEffect } from 'react';
import DataTable from 'react-data-table-component';
import { useForm } from 'react-hook-form';
import useGetFoodItemService from '../hooks/useGetFoodItemService'; // Adjust import as necessary
import Papa from 'papaparse';
import { ItemData, ItemsForm } from '../../../../types/ItemsForm'; // Import your types

const FoodItemlist: React.FC = ({itemsData=[]}:{itemsData?:ItemData[]}) => {
  
    // Edit item handler
  const handleEdit = (item: ItemData) => {
    
  };
   // Delete item handler
  const handleDelete = async (id: number) => {
    
  };


  return (
    <DataTable
    title="Items List"
    columns={[
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
    ]}
    data={itemsData}
    pagination
    selectableRows
    highlightOnHover
    />
    );
};

export default FoodItemlist; // Ensure this export is present
