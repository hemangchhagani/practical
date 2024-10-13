import React from 'react';
import { useForm } from 'react-hook-form';
import AuthService from './AuthService';

interface IRegisterForm {
  username: string;
  email: string;
  password: string;
  password_confirmation: string;
}

const Register: React.FC = () => {
  const { register, handleSubmit, formState: { errors } } = useForm<IRegisterForm>();

  const onSubmit = (data: IRegisterForm) => {
    AuthService.register(data.username, data.email, data.password)
      .then(() => {
        alert('Registration successful!');
      })
      .catch(error => {
        console.error('Error during registration:', error);
      });
  };

  return (
    <div className="container mt-5">
    <div className="row justify-content-center">
    <div className="col-md-6">
      <h2 className="text-center mb-4">Register</h2>
      <form onSubmit={handleSubmit(onSubmit)}>
        {/* Name Field */}
        <div className="form-group mb-3">
          <label htmlFor="name">Username</label>
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
       
        {/* Submit Button */}
        <button type="submit" className="btn btn-primary w-100">Register</button>
      </form>
    </div>
  </div>
</div>
);
};

export default Register;
