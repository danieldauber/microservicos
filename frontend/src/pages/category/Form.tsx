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
import { useForm } from "react-hook-form";
import categoryHttp from "../../util/http/category-http";
import * as Yup from "../../util/vendor/yup";
import { yupResolver } from "@hookform/resolvers/yup";
import { useHistory, useParams } from "react-router";
import { useSnackbar } from "notistack";

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
  const snackbar = useSnackbar();

  const history = useHistory();
  const { id } = useParams<{ id?: string }>();
  const [category, setCategory] = useState<Inputs>();
  const [loading, setLoading] = useState<boolean>(false);

  const buttonProps: ButtonProps = {
    className: classes.submit,
    color: "secondary",
    variant: "contained",
    disabled: loading,
  };

  const {
    register,
    handleSubmit,
    getValues,
    setValue,
    formState: { errors },
    reset,
    watch,
  } = useForm<Inputs>({
    defaultValues: {
      is_active: true,
    },
    resolver: yupResolver(schema),
  });

  useEffect(() => {
    if (!id) {
      return;
    }

    async function getCategory() {
      setLoading(true);
      try {
        categoryHttp.get(id).then(({ data }) => {
          setCategory(data.data);
          reset(data.data);
        });
      } catch (error) {
        console.error(error);
        snackbar.enqueueSnackbar("Não foi possível carregar as informações", {
          variant: "error",
        });
      } finally {
        setLoading(false);
      }
    }

    getCategory();
  }, [id]);

  async function onSubmit(formData: Inputs, event: any) {
    setLoading(true);
    try {
      const http = !category
        ? categoryHttp.create(formData)
        : categoryHttp.update(category.id, formData);
      const { data } = await http;
      snackbar.enqueueSnackbar("Categoria salva com sucesso", {
        variant: "success",
      });
      setTimeout(() => {
        event
          ? id
            ? history.replace(`/categories/${data.data.id}/edit`)
            : history.push(`/categories/${data.data.id}/edit`)
          : history.push("/categories");
      });
    } catch (error) {
      console.error(error);
      snackbar.enqueueSnackbar("Não foi possível salvar a categoria", {
        variant: "error",
      });
    } finally {
      setLoading(false);
    }
  }

  // useEffect(() => {
  //   register({ name: "is_active" });
  // }, [register]);

  return (
    <form onSubmit={handleSubmit(onSubmit)}>
      <TextField
        label="Nome"
        fullWidth
        variant={"outlined"}
        disabled={loading}
        error={errors.name !== undefined}
        helperText={errors.name && errors.name.message}
        InputLabelProps={{ shrink: true }}
        {...register("name")}
      />
      <TextField
        label="Descrição"
        multiline
        rows="4"
        fullWidth
        variant={"outlined"}
        margin={"normal"}
        disabled={loading}
        {...register("description")}
        InputLabelProps={{ shrink: true }}
      />
      <FormControlLabel
        disabled={loading}
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
          color={"primary"}
          {...buttonProps}
          onClick={() => onSubmit(getValues(), null)}
        >
          Salvar
        </Button>
        <Button type="submit" {...buttonProps}>
          Salvar e continuar editando
        </Button>
      </Box>
    </form>
  );
};
