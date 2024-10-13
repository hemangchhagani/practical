import React from 'react';
import logo from './logo.svg';
import './App.css';
import { BrowserRouter as Router, Route, Routes, Link, useNavigate, useParams } from "react-router-dom";
import Login from './components/Login';
import Register from './components/Register';
import Items from './components/items/ReadItems';
import Residents from './components/residents/ReadResidents';


function App() {
  return (
     <Router>
      <Routes>
        <Route path="/" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/items" element={<Items />} />
        <Route path="/residents" element={<Residents />} />
       
      </Routes>
    </Router>
  );
}

export default App;
