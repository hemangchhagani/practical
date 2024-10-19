import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Login from "../components/pages/Login";
import Items from "../components/pages/Items";
import Residents from "../components/pages/Residents";
import Register from "../components/pages/Register";

const AppRoutes = () => {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/food-items" element={<Items />} />
        <Route path="/food-residents" element={<Residents />} />
      </Routes>
    </Router>
  );
};

export default AppRoutes;
// Add this to make the file a module
export {};
