import React from 'react';
import logo from './logo.svg';
import './App.css';
import { BrowserRouter as Router, Route, Routes, Link, useNavigate, useParams } from "react-router-dom";
import AppRoutes from './routes';

const App: React.FC = () => {
  return <AppRoutes />;
};

export default App;
