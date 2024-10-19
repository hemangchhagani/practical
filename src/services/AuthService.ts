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

  login: (email: string, password: string) => {
    return axios
      .post(API_URL + '/login', { email, password })
      .then((response) => {
        if (response.data.token) {
          localStorage.setItem('user', JSON.stringify(response.data));
          return response.data;
        } else {
          throw new Error('Invalid login credentials.');
        }
      })
      .catch((error) => {
        console.error('Error during login:', error);
        // Log the error response, if available
        if (error.response) {
          console.error('Response data:', error.response.data);
          console.error('Response status:', error.response.status);
          console.error('Response headers:', error.response.headers);
        }
        throw error; // Re-throw the error if necessary
      });
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
