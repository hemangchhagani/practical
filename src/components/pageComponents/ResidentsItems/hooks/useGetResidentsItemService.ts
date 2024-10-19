import axios from "axios";

const API_URL = process.env.REACT_APP_API_URL;

const useGetResidentsItemService = () => {

  const getResidents = () => {
    return axios.get(`${API_URL}/residents`);
  };

  const setResidents = (name: string, category: string, iddsi_level: string) => {
    return axios.post(`${API_URL}/residents`, { name, category, iddsi_level });
  };

  const updateResidents = (
    id: number,
    name: string,
    category: string,
    iddsi_level: string
  ) => {
    return axios.put(`${API_URL}/residents/${id}`, {
      name,
      category,
      iddsi_level,
    });
  };

  const deleteResidents = (id: number) => {
    return axios.delete(`${API_URL}/residents/${id}`);
  };

  return { getResidents, setResidents, updateResidents, deleteResidents };
};

export default useGetResidentsItemService;
