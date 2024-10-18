// src/common/Commonservice.ts
import Papa from 'papaparse';


// Example common function (you can add others later)
const parseCsv = (file: File, callback: (results: any) => void) => {
  Papa.parse(file, {
    complete: callback,
    header: false,
  });
};

export default { parseCsv };
