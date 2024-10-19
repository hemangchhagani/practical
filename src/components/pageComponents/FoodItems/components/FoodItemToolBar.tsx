interface FoodItemToolBarProps {
  handleShow: () => void;
  setShowCsvModal: (showCsvModal: boolean) => void;
}

export const FoodItemToolBar: React.FC<FoodItemToolBarProps> = ({
  handleShow = () => {},
  setShowCsvModal = () => {},
}) => {
  return (
    <div className="col-md-12 align-right">
      <button
        type="button"
        className="btn btn-primary m-3"
        onClick={handleShow}
      >
        Add Food Item
      </button>
      <button
        type="button"
        className="btn btn-primary m-3"
        onClick={() => setShowCsvModal(true)}
      >
        Add Food Items (CSV)
      </button>
      <a href="/food-residents" className="btn btn-primary m-3">
         Residents List
      </a>
    </div>
  );
};
