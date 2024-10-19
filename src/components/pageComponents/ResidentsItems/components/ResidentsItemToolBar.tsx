interface ResidentsItemToolBarProps {
  handleShow: () => void;
  setShowCsvModal: (showCsvModal: boolean) => void;
}

export const ResidentsItemToolBar: React.FC<ResidentsItemToolBarProps> = ({
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
        Add Residents
      </button>
      <button
        type="button"
        className="btn btn-primary m-3"
        onClick={() => setShowCsvModal(true)}
      >
        Add Residents (CSV)
      </button>
      <a href="/food-items" className="btn btn-primary m-3">
        Food Items List
      </a>
    </div>
  );
};
