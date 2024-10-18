import React from 'react';
import { useForm } from 'react-hook-form';
import { useNavigate } from 'react-router-dom';
import { ILoginForm } from '../../types/index';  // Correct import
import AuthService from '../../services/AuthService';
import logo from '../../assets/images/logo.png';

const Login: React.FC = () => {
  const { register, handleSubmit, formState: { errors } } = useForm<ILoginForm>();
  const navigate = useNavigate();

  const onSubmit = (data: ILoginForm) => {
    AuthService.login(data.email, data.password)
      .then(() => {
        alert('Login successful!');
        navigate('/items'); // Redirect after login
      })
      .catch(error => {
        alert('Error during login: ' + error);
      });
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
          </form>
        </div>
      </div>
    </div>
  );
};

export default Login;
