import { useState, useEffect } from 'react';
import axios from 'axios';
import AuthService from '../services/AuthService';

//const API_URL = 'http://127.0.0.1:8000'; // Change to your API endpoint
const API_URL = process.env.REACT_APP_API_URL;
const register = (username: string, email: string, password: string) => {
  return axios.post(API_URL + '/register', {
    username,
    email,
    password
  });
};

const login = (email: string, password: string) => {
  return axios.post(API_URL + '/login', {
    email,
    password
  },{ headers: {
    'Content-Type': 'application/json'
}}).then(response => {
  if (response.data.token) {
    localStorage.setItem('user', JSON.stringify(response.data));
    return response.data; // Successful login
  } else {
    // If no token, login is unsuccessful (server didn't provide token)
    throw new Error('Invalid login credentials.');
  }
  });
};

const logout = () => {
  localStorage.removeItem('user');
};

const getCurrentUser = () => {
  const userStr = localStorage.getItem('user');
  if (userStr) return JSON.parse(userStr);
  return null;
};


const useAuth = () => {
  const [isAuthenticated, setIsAuthenticated] = useState(false);

  useEffect(() => {
    const checkAuth = async () => {
      try {
        const response = await AuthService.checkAuth();
        setIsAuthenticated(response.isAuthenticated);
      } catch (error) {
        setIsAuthenticated(false);
      }
    };
    
    checkAuth();
  }, []);

  return { isAuthenticated };
};



export default {
  useAuth,
  register,
  login,
  logout,
  getCurrentUser
};
