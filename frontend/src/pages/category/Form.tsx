import React, { useEffect, useState } from "react";
import {
  Box,
  Button,
  Checkbox,
  FormControlLabel,
  makeStyles,
  TextField,
  Theme,
} from "@material-ui/core";
import { ButtonProps } from "@material-ui/core/Button";
import useForm from "react-hook-form";
import categoryHttp from "../../util/http/category-http";
import * as Yup from "../../util/vendor/yup";
import { yupResolver } from "@hookform/resolvers/yup";
import { useParams } from "react-router";

const useStyles = makeStyles((theme: Theme) => {
  return {
    submit: {
      margin: theme.spacing(1),
    },
  };
});

interface Inputs {
  id: string;
  name: string;
  description: string;
  is_active: boolean;
}

const schema = Yup.object().shape({
  name: Yup.string().label("Nome").max(255).required(),
  is_active: Yup.boolean(),
});

export const Form = () => {
  const classes = useStyles();

  const buttonProps: ButtonProps = {
    className: classes.submit,
    color: "secondary",
    variant: "contained",
    // disabled: loading,
  };
  const { id } = useParams<{ id?: string }>();
  const [category, setCategory] = useState<Inputs>();
  // const [loading, setLoading] = useState<boolean>(false);

  const { register, handleSubmit, getValues, setValue, errors, reset, watch } =
    useForm<Inputs>({
      defaultValues: {
        is_active: true,
      },
    });

  useEffect(() => {
    if (!id) {
      return;
    }

    async function getCategory() {
      // setLoading(true);
      try {
        categoryHttp.get(id).then(({ data }) => {
          setCategory(data.data);
          reset(data.data);

          // Object.keys(data.data).map((value: any, i: any) =>
          //   setValue(value, i)
          // );
        });
      } catch (error) {
        console.error(error);
        // snackbar.enqueueSnackbar("Não foi possível carregar as informações", {
        //   variant: "error",
        // });
      } finally {
        // setLoading(false);
      }
    }

    getCategory();
  }, [id, reset]);

  function onSubmit(formData: Inputs, event: any) {
    console.log(formData);
    console.log(errors);
    reset();

    const http = !category
      ? categoryHttp.create(formData)
      : categoryHttp.update(category.id, formData);

    http.then((response) => console.log(response));
    //salvar e editar
    //salvar
    // categoryHttp.create(formData).then((response) => console.log(response));
  }

  useEffect(() => {
    register({ name: "is_active" });
  }, [register]);

  return (
    <form onSubmit={handleSubmit(onSubmit)}>
      <TextField
        label="Nome"
        fullWidth
        variant={"outlined"}
        error={errors.name !== undefined}
        helperText={errors.name && errors.name.message}
        InputLabelProps={{ shrink: true }}
        name="name"
      />
      <TextField
        label="Descrição"
        multiline
        rows="4"
        fullWidth
        variant={"outlined"}
        margin={"normal"}
        name="description"
        InputLabelProps={{ shrink: true }}
      />
      <FormControlLabel
        // disabled={loading}
        control={
          <Checkbox
            name="is_active"
            color={"primary"}
            onChange={() => setValue("is_active", !getValues()["is_active"])}
            checked={watch("is_active")}
          />
        }
        label={"Ativo?"}
        labelPlacement={"end"}
      />
      <Box dir={"rtl"}>
        <Button
          type="submit"
          color={"primary"}
          {...buttonProps}
          onClick={() => onSubmit(getValues(), null)}
        >
          Salvar
        </Button>
        <Button {...buttonProps} type="submit">
          Salvar e continuar editando
        </Button>
      </Box>
    </form>
  );
};
