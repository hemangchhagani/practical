import React, { useState, useEffect } from 'react';
import DataTable from 'react-data-table-component';
import { useForm } from 'react-hook-form';
import Commonservice from '../Commonservices';
import Papa from 'papaparse';

interface ResidentsData {
  id: number;
  name: string;
  iddsi_level: string;
}

interface ResidentsForm {
  id: number;
  name: string;
  iddsi_level: string;
}

const ReadResidents: React.FC = () => {
  const { register, handleSubmit, setValue, formState: { errors } } = useForm<ResidentsForm>();
  const [residentsData, setResidentsData] = useState<ResidentsData[]>([]);
  const [showModal, setShowModal] = useState(false);
  const [editMode, setEditMode] = useState(false);
  const [currentResidents, setCurrentResidents] = useState<ResidentsData | null>(null);
  const [showCsvModal, setShowCsvModal] = useState(false);

  // Fetch data dynamically when component mounts
  useEffect(() => {
    fetchResidentsData();
  }, []);

  const fetchResidentsData = async () => {
    try {
      const response = await Commonservice.getResidents();
      const Residents = response.data || response;
      // Map and format the response to match your state structure
      const formattedResidents = Residents.map((resident: any) => ({
        id: resident.id,
        name: resident.name,
        iddsi_level: resident.iddsi_level,
      }));
      setResidentsData(formattedResidents);
    } catch (error) {
      console.error('Error fetching Residents data:', error);
    }
  };

  const handleShow = () => {
    setShowModal(true);
    setEditMode(false);
  };

  const handleClose = () => {
    setShowModal(false);
    setCurrentResidents(null);
    if (showCsvModal) {
      setShowCsvModal(false);
    }
  };

  // CSV Upload Handler
  const handleCsvUpload = async (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (file) {
      Papa.parse(file, {
        complete: async function (results: any) {
          const data = results.data;
          const parsedResidents: ResidentsData[] = data.slice(1).map((row: any, index: number) => ({
            id: residentsData.length + index + 1,
            name: row[0],
            iddsi_level: row[1],
          }));

          for (const resident of parsedResidents) {
            try {
              await Commonservice.setResidents(resident.name, resident.iddsi_level);
            } catch (error) {
              console.error('Error uploading Residents:', resident, error);
            }
          }

          fetchResidentsData();
          alert("Residents uploaded and saved successfully!");
        },
        header: false,
      });
    }
    handleClose();
  };

  const handleShowCsvModal = () => {
    setShowCsvModal(true);
  };

  const onSubmit = async (data: ResidentsForm) => {
    if (editMode && currentResidents) {
      // Logic for updating the Residents
      try {
        await Commonservice.updateResidents(currentResidents.id, data.name, data.iddsi_level); 
        const updatedResidents = residentsData.map(resident =>
          resident.id === currentResidents.id ? { ...resident, name: data.name, iddsi_level: data.iddsi_level } : resident
        );
        setResidentsData(updatedResidents);
        alert('Residents updated successfully!');
      } catch (error) {
        console.error('Error updating Residents:', error);
      }
    } else {
      // Logic for creating a new Residents
      try {
        await Commonservice.setResidents(data.name, data.iddsi_level);
        alert('Residents inserted successfully!');

        const newResidents: ResidentsData = {
          id: residentsData.length + 1, // Replace this with the id from backend if available
          name: data.name,
          iddsi_level: data.iddsi_level,
        };

        setResidentsData([...residentsData, newResidents]);
      } catch (error) {
        console.error('Error during Residents insertion:', error);
      }
    }
    handleClose();
  };

  // Edit Residents handler
  const handleEdit = (residents: ResidentsData) => {
    setEditMode(true);
    setCurrentResidents(residents);
    setShowModal(true);
    setValue('id', residents.id);
    setValue('name', residents.name);
    setValue('iddsi_level', residents.iddsi_level);
  };

  // Delete Residents handler
  const handleDelete = async (id: number) => {
    try {
      await Commonservice.DeleteResidents(id);
      const filteredResidents = residentsData.filter(residents => residents.id !== id);
      setResidentsData(filteredResidents); 
      alert(`Residents with ID ${id} deleted successfully.`);
    } catch (error) {
      console.error(`Error deleting Residents with ID ${id}:`, error);
      alert(`Error deleting Residents with ID ${id}`);
    }
  };

  const columns = [
    {
      name: 'ID',
      selector: (row: ResidentsData) => row.id,
      sortable: true,
    },
    {
      name: 'Name',
      selector: (row: ResidentsData) => row.name,
      sortable: true,
    },
    {
      name: 'IDDSI Level',
      selector: (row: ResidentsData) => row.iddsi_level,
      sortable: true,
    },
    {
      name: 'Actions',
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
            onClick={() => handleDelete(row.id)}
          >
            Delete
          </button>
        </>
      ),
      ignoreRowClick: true, // Prevent row clicks from firing actions
    },
  ];

  return (
    <div className="container mt-5">
      <div className="row justify-content-center">
        <div className="col-md-12">
          <h2 className="text-center">Residents</h2>
          <div className="col-md-12 align-right">
            <button type="button" className="btn btn-primary m-3 w100" onClick={handleShow}>
              Add Resident
            </button>
            <button type="button" className="btn btn-primary m-3 w100" onClick={handleShowCsvModal}>
              Add Resident bulk using CSV
            </button>
            <a href="/items" className="btn-primary m-3 w100" >
              Add items
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
                        {editMode ? 'Edit Resident' : 'Add New Resident'}
                      </h5>
                      <button type="button" className="close" aria-label="Close" onClick={handleClose}>
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div className="modal-body">
                      <form onSubmit={handleSubmit(onSubmit)}>
                        <input {...register('id')} type="hidden" id="id" />
                        <div className="form-group mb-3">
                          <label htmlFor="name">Name</label>
                          <input
                            {...register('name', { required: true })}
                            type="text"
                            className="form-control"
                            id="name"
                            placeholder="Enter resident name"
                          />
                          {errors.name && <span className="text-danger">This field is required</span>}
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
                          {editMode ? 'Update Resident' : 'Add Resident'}
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
            title="Residents List"
            columns={columns}
            data={residentsData}
            pagination
            selectableRows
            highlightOnHover
          />
        </div>
      </div>
    </div>
  );
};

export default ReadResidents;
