import axios from 'axios';

//const API_URL = 'http://127.0.0.1:8000'; // Change to your API endpoint
const API_URL = process.env.REACT_APP_API_URL;

const AuthService = {
  register: (username: string, email: string, password: string) => {
    return axios.post(API_URL + '/register', {
      username,
      email,
      password
    });
  },

  async login(email: string, password: string) {
    try {
      const response = await axios.post(API_URL + '/login', { email, password });
      
      // Check if response is successful and has a status code 200
      if (response.status === 200 ) {
        const token = response.data.data.token;
        localStorage.setItem('token', token); // Store token in localStorage
        return response.data; // Return the entire response for further use
      } else {
        throw new Error(response.data.message || 'Invalid login credentials');
      }
    } catch (error: any) {
      throw new Error(error.response?.data?.message || 'Error during login');
    }
  },

  logout: () => {
    localStorage.removeItem('user');
  },

  getCurrentUser: () => {
    const userStr = localStorage.getItem('user');
    if (userStr) return JSON.parse(userStr);
    return null;
  },

  // Add the checkAuth method
  checkAuth() {
    const user = this.getCurrentUser(); // Check if the user is stored locally
    if (user && user.token) {
      // You can also verify the token by sending a request to the backend if necessary
      return Promise.resolve({ isAuthenticated: true });
    } else {
      return Promise.resolve({ isAuthenticated: false });
    }
  },
};

export default AuthService;
