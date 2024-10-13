import React from 'react';
import { useForm } from 'react-hook-form';
import { useNavigate } from 'react-router-dom';
interface ILoginForm {
  email: string;
  password: string;
}

const Items: React.FC = () => {
  const { register, handleSubmit, formState: { errors } } = useForm<ILoginForm>();
  const navigate = useNavigate();

  const onSubmit = (data: ILoginForm) => {
  };

  return (
    <div>
      <h2>Items</h2>
    </div>
  );
};

export default Items;
