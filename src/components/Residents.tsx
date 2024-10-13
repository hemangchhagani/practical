import React from 'react';
import { useForm } from 'react-hook-form';
import { useNavigate } from 'react-router-dom';

interface ILoginForm {
  email: string;
  password: string;
}

const Residents: React.FC = () => {
  const { register, handleSubmit, formState: { errors } } = useForm<ILoginForm>();
  const navigate = useNavigate();

  const onSubmit = (data: ILoginForm) => {
    // AuthService.login(data.email, data.password)
    //   .then(() => {
    //     alert('Login successful!');
    //     navigate('/'); // Redirect after login
    //   })
    //   .catch(error => {
    //     console.error('Error during login:', error);
    //   });
  };

  return (
    <div>
      <h2>Login</h2>
      <form onSubmit={handleSubmit(onSubmit)}>
        <div>
          <label>Email</label>
          <input
            {...register('email', { required: true })}
            type="email"
          />
          {errors.email && <span>This field is required</span>}
        </div>
        
        <div>
          <label>Password</label>
          <input
            {...register('password', { required: true })}
            type="password"
          />
          {errors.password && <span>This field is required</span>}
        </div>
        
        <button type="submit">Login</button>
      </form>
    </div>
  );
};

export default Residents;
