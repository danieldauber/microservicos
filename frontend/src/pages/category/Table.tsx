import * as React from "react";
import MUIDataTable, { MUIDataTableColumn } from "mui-datatables";
import { useEffect, useState } from "react";
import { httpVideo } from "../../util/http";
import { Chip } from "@material-ui/core";

import { format, parseISO } from "date-fns";

const columnsDefinition: MUIDataTableColumn[] = [
  {
    name: "name",
    label: "Nome",
  },
  {
    name: "is_active",
    label: "Ativo?",
    options: {
      customBodyRender(value, tableMeta, updateValue) {
        return value ? (
          <Chip label="Sim" color="primary" />
        ) : (
          <Chip label="Não" color="secondary" />
        );
      },
    },
  },
  {
    name: "created_at",
    label: "Criado em",
    options: {
      customBodyRender(value, tableMeta, updateValue) {
        return <span>{format(parseISO(value), "dd/MM/Y")}</span>;
      },
    },
  },
];

type Props = {};
const Table = (props: Props) => {
  const [data, setData] = useState([]);

  useEffect(() => {
    httpVideo.get("categories").then((response) => setData(response.data.data));
  }, []);

  return <MUIDataTable title="" columns={columnsDefinition} data={data} />;
};

export default Table;
