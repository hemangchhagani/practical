import axios from 'axios';

//const API_URL = 'http://127.0.0.1:8000'; // Change to your API endpoint
const API_URL = process.env.REACT_APP_API_URL;
//data.name, data.category, data.iddsi_level
const setItems = (name: string, category: string, iddsi_level: string ) => {
  return axios.post(API_URL + '/food-items', {
        name,
        category,
        iddsi_level
  });
};

const getItems = () => {
    return axios.get(API_URL + '/food-items');
};

const editItems = (name: string, category: string, iddsi_level: string) => {
    return axios.post(API_URL + '/food-items', {
        name,
        category,
        iddsi_level
    });
};


const updateItems = async (id: number, name: string, category: string, iddsi_level: string) => {
    console.log(id);
    const response = await axios.put(API_URL + `/food-items`, { id , name, category, iddsi_level }, {
        headers: {
            'Content-Type': 'application/json',
        }});
    return response.data;
};

const DeleteItems = async (id: number) => {
    try {
      const response = await axios.delete(`${API_URL}/food-items/${id}`, {
        headers: {
          'Content-Type': 'application/json',
        },
      });
      return response.data; // Return the response data (if needed)
    } catch (error) {
      console.error(`Error deleting item with ID ${id}:`, error);
      throw error; // Throw the error for further handling if needed
    }
  };


  // residents
  const setResidents = (name: string,iddsi_level: string ) => {
    return axios.post(API_URL + '/residents', {
          name,
          iddsi_level
    });
  };
  
  const getResidents = () => {
      return axios.get(API_URL + '/residents');
  };

  const editResidents = (name: string, iddsi_level: string) => {
      return axios.post(API_URL + '/residents', {
          name,
          
          iddsi_level
      });
  };
  
  const updateResidents = async (id: number, name: string, iddsi_level: string) => {
      console.log(id);
      const response = await axios.put(API_URL + `/residents`, { id , name, iddsi_level }, {
          headers: {
              'Content-Type': 'application/json',
          }});
      return response.data;
  };
  
  const DeleteResidents = async (id: number) => {
      try {
        const response = await axios.delete(`${API_URL}/residents/${id}`, {
          headers: {
            'Content-Type': 'application/json',
          },
        });
        return response.data; // Return the response data (if needed)
      } catch (error) {
        console.error(`Error deleting item with ID ${id}:`, error);
        throw error; // Throw the error for further handling if needed
      }
    };

export default {
  getItems,
  setItems,
  editItems,
  updateItems,
  DeleteItems,
  setResidents,
  getResidents,
  editResidents,
  updateResidents,
  DeleteResidents



  
};
