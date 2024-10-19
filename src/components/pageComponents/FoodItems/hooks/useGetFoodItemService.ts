// src/services/ItemService.ts
import axios from "axios";

const API_URL = process.env.REACT_APP_API_URL;

const useGetFoodItemService = () => {
  const getItems = () => {
    return axios.get(`${API_URL}/food-items`);
  };

  const setItems = (name: string, category: string, iddsi_level: string) => {
    return axios.post(`${API_URL}/food-items`, { name, category, iddsi_level });
  };

  const updateItems = (
    id: number,
    name: string,
    category: string,
    iddsi_level: string
  ) => {
    return axios.put(`${API_URL}/food-items/${id}`, {
      name,
      category,
      iddsi_level,
    });
  };

  const deleteItems = (id: number) => {
    return axios.delete(`${API_URL}/food-items/${id}`);
  };

  return { getItems, setItems, updateItems, deleteItems };
};

export default useGetFoodItemService;
