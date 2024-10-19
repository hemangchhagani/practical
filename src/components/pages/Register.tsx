import React from 'react';
import { useForm } from 'react-hook-form';
import AuthService from '../../services/AuthService';
import { IRegisterForm } from '../../types/index';  // Correct import

// Define an interface for the expected error structure
interface ApiError {
  response?: {
    data: {
      message: string;
    };
  };
}

const Register: React.FC = () => {
  const { register, handleSubmit, formState: { errors } } = useForm<IRegisterForm>();
  const [serverError, setServerError] = React.useState<string | null>(null); // State for server error messages

  const onSubmit = async (data: IRegisterForm) => {
    try {
      await AuthService.register(data.username, data.email, data.password);
      alert('Registration successful!');
      // Optionally, redirect to login or another page here
    } catch (error) {
      console.error('Error during registration:', error);
      
      // Use type assertion to access the error response
      const apiError = error as ApiError;

      if (apiError.response && apiError.response.data.message) {
        setServerError(apiError.response.data.message); // Set server error message if exists
      } else {
        setServerError('An unknown error occurred.'); // Fallback error message
      }
    }
  };

  return (
    <div className="container mt-5">
      <div className="row justify-content-center">
        <div className="col-md-6">
          <h2 className="text-center mb-4">Register</h2>
          <form onSubmit={handleSubmit(onSubmit)}>
            {/* Name Field */}
            <div className="form-group mb-3">
              <label htmlFor="username">Username</label>
              <input
                {...register('username', { required: true })}
                type="text"
                className="form-control"
                id="username"
                placeholder="Enter your username"
              />
              {errors.username && <span className="text-danger">This field is required</span>}
            </div>
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
            
            {/* Server Error Message */}
            {serverError && <div className="text-danger mb-3">{serverError}</div>}

            {/* Submit Button */}
            <button type="submit" className="btn btn-primary w-100">Register</button>
          </form>
          <div className='col-md-12 mt-3'>
            <a href='/' className="btn-primary">Login</a>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Register;