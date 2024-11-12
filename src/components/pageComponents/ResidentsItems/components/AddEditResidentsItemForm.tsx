import { useForm } from "react-hook-form";
import { ResidentsData, ResidentsForm } from "../../../../types/ResidentsForm";
import useGetFoodItemService from "../hooks/useGetResidentsItemService";

interface AddEditResidantsFormProps {
  editMode: boolean;
  handleClose: () => void;
  setResidentsData: (ResidentsData: ResidentsData[]) => void;
  ResidentsData: ResidentsData[];
  currenResidents: ResidentsData;
}

export const AddEditResidentsItemForm: React.FC<AddEditResidantsFormProps> = ({
  editMode,
  handleClose,
  setResidentsData,
  ResidentsData,
  currenResidents,
}) => {
  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue, // We need this for setting default values manually
  } = useForm<ResidentsForm>({
    defaultValues: editMode
      ? {
          name: currenResidents.name,
          category: currenResidents.category,
          iddsi_level: currenResidents.iddsi_level,
        }
      : {
          name: "",
          category: "",
          iddsi_level: "",
        },
  });

  const { setResidents, updateResidents } = useGetFoodItemService();

  // Handle form submission
  const onSubmit = async (data: ResidentsForm) => {
    try {
      if (editMode && currenResidents) {
        // Edit existing item
        await updateResidents(
          currenResidents.id,
          data.name,
          data.category,
          data.iddsi_level
        );
        setResidentsData(
          ResidentsData.map((item) =>
            item.id === currenResidents.id
              ? {
                  ...item,
                  name: data.name,
                  category: data.category,
                  iddsi_level: data.iddsi_level,
                }
              : item
          )
        );
        alert("Residents updated successfully!");
      } else {
        // Add new item
        const response = await setResidents(
          data.name,
          data.category,
          data.iddsi_level
        );
        setResidentsData([...ResidentsData, response.data]);
        alert("New Residents added successfully!");
      }
      handleClose(); // Close modal after success
    } catch (error) {
      console.error("Error submitting form:", error);
      alert("Error saving item.");
    }
  };

  return (
    <>
      <div className="modal show d-block" role="dialog">
        <div className="modal-dialog">
          <div className="modal-content">
            <div className="modal-header">
              <h5 className="modal-title">
                {editMode ? "Edit Residents" : "Add New Residents"}
              </h5>
              <button type="button" className="close" onClick={handleClose}>
                <span>&times;</span>
              </button>
            </div>
            <div className="modal-body">
              <form onSubmit={handleSubmit(onSubmit)}>
                <div className="form-group mb-3">
                  <label htmlFor="name">Name</label>
                  <input
                    {...register("name", { required: true })}
                    type="text"
                    className="form-control"
                    placeholder="Enter Residents name"
                  />
                  {errors.name && (
                    <span className="text-danger">This field is required</span>
                  )}
                </div>
                <div className="form-group mb-3">
                  <label htmlFor="category">Category</label>
                  <select
                    {...register("category", { required: true })}
                    className="form-control"
                  >
                    <option value="">Select Category</option>
                    <option value="chicken">Chicken</option>
                    <option value="pork">Pork</option>
                    <option value="fish">Fish</option>
                    <option value="veg">Veg</option>
                  </select>
                  {errors.category && (
                    <span className="text-danger">Category is required</span>
                  )}
                </div>
                <div className="form-group mb-3">
                  <label htmlFor="iddsi_level">IDDSI Level</label>
                  <select
                    {...register("iddsi_level", { required: true })}
                    className="form-control"
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
                  {errors.iddsi_level && (
                    <span className="text-danger">IDDSI Level is required</span>
                  )}
                </div>
                <button type="submit" className="btn btn-primary w-100">
                  {editMode ? "Update Residents" : "Add Residents"}
                </button>
              </form>
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
