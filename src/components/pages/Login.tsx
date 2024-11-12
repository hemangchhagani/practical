import React from 'react';
import { useForm } from 'react-hook-form';
import { useNavigate } from 'react-router-dom';
import { ILoginForm } from '../../types/index';
import AuthService from '../../services/AuthService';
import logo from '../../assets/images/logo.png';

const Login: React.FC = () => {
  const { register, handleSubmit, formState: { errors } } = useForm<ILoginForm>();
  const navigate = useNavigate();

  // Make the onSubmit function async
  const onSubmit = async (data: ILoginForm) => {
    try {
      // Await the response from AuthService.login
      const response = await AuthService.login(data.email, data.password);
     // Check if the login was successful
      if (response.status === 200) {
        alert(response.message); // Show "Login successful"
        localStorage.setItem('token', response.data.token); // Store the token if needed
        navigate('/food-items'); // Redirect after successful login
      } else {
        alert('Login failed: ' + response.message);
      }
    } catch (error: any) {
      // Handle errors correctly
      console.error('Error during login:', error);
      alert('Error during login: ' + (error.response?.data?.message || error.message));
    }
  };

  return (
    <div className="container mt-5">
    <div className="row justify-content-center">
    <div className="col-md-6">
    <div className="m-5">
    <img src={logo} alt="Logo" className="mb-4" />
    </div>
    <h2 className="text-center mb-4">Login</h2>
    <form onSubmit={handleSubmit(onSubmit)}>
            {/* Email Field */}
    <div className="form-group mb-3">
    <label htmlFor="email">Email</label>
    <input
    {...register('email', { required: true })}
    type="email"
    className="form-control"
    id="email"
    placeholder="Enter your email"
    />
    {errors.email && <span className="text-danger">This field is required</span>}
    </div>
            {/* Password Field */}
    <div className="form-group mb-3">
    <label htmlFor="password">Password</label>
    <input
    {...register('password', { required: true })}
    type="password"
    className="form-control"
    id="password"
    placeholder="Enter your password"
    />
    {errors.password && <span className="text-danger">This field is required</span>}
    </div>
            {/* Submit Button */}
    <button type="submit" className="btn btn-primary w-100">Login</button>
    <div className='col-md-12 mt-3'>
    <a href='/register' className="btn-primary">Register</a>
    </div>
    </form>
    </div>
    </div>
    </div>
    );
};

export default Login;
