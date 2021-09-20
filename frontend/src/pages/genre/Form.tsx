import * as React from "react";
import {
  Box,
  Button,
  makeStyles,
  MenuItem,
  TextField,
  Theme,
} from "@material-ui/core";
import { ButtonProps } from "@material-ui/core/Button";
import { useForm } from "react-hook-form";
import { useEffect, useState } from "react";
import categoryHttp from "../../util/http/category-http";
import genreHttp from "../../util/http/genre-http";
import { useSnackbar } from "notistack";
import { useHistory, useParams } from "react-router";
import * as yup from "../../util/vendor/yup";
import { yupResolver } from "@hookform/resolvers/yup";

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
  categories_id: Object;
}

const validationSchema = yup.object().shape({
  name: yup.string().label("Nome").required().max(255),
  categories_id: yup.array().label("Categorias").required(),
});

export const Form = () => {
  const {
    register,
    handleSubmit,
    getValues,
    setValue,
    watch,
    formState: { errors },
    reset,
  } = useForm({
    resolver: yupResolver(validationSchema),
  });

  const classes = useStyles();
  const snackbar = useSnackbar();
  const history = useHistory();
  const { id } = useParams<{ id?: string }>();
  const [genre, setGenre] = useState<{ id: string } | null>(null);
  const [categories, setCategories] = useState<any[]>([]);
  const [loading, setLoading] = useState<boolean>(false);

  const buttonProps: ButtonProps = {
    className: classes.submit,
    color: "secondary",
    variant: "contained",
    disabled: loading,
  };

  useEffect(() => {
    async function loadData() {
      setLoading(true);
      const promises = [categoryHttp.list()];
      if (id) {
        promises.push(genreHttp.get(id));
      }
      try {
        const [categoriesResponse, genreResponse] = await Promise.all(promises);
        setCategories(categoriesResponse.data.data);
        if (id) {
          setGenre(genreResponse.data.data);
          reset({
            ...genreResponse.data.data,
            categories_id: genreResponse.data.data.categories.map(
              (category: any) => category.id
            ),
          });
        }
      } catch (error) {
        console.error(error);
        snackbar.enqueueSnackbar("Não foi possível carregar as informações", {
          variant: "error",
        });
      } finally {
        setLoading(false);
      }
    }

    loadData();
  }, [id, reset, snackbar]);

  // useEffect(() => {
  //   register({ name: "categories_id" });
  // }, [register]);

  async function onSubmit(formData: any, event: any) {
    console.log(formData);

    setLoading(true);
    try {
      const http = !genre
        ? genreHttp.create(formData)
        : genreHttp.update(genre.id, formData);
      const { data } = await http;
      snackbar.enqueueSnackbar("Gênero salvo com sucesso", {
        variant: "success",
      });
      setTimeout(() => {
        event
          ? id
            ? history.replace(`/genres/${data.data.id}/edit`)
            : history.push(`/genres/${data.data.id}/edit`)
          : history.push("/genres");
      });
    } catch (error) {
      console.error(error);
      snackbar.enqueueSnackbar("Não foi possível salvar o gênero", {
        variant: "error",
      });
    } finally {
      setLoading(false);
    }
  }

  return (
    <form onSubmit={handleSubmit(onSubmit)}>
      <TextField
        {...register("name")}
        label="Nome"
        fullWidth
        variant={"outlined"}
        disabled={loading}
        error={errors.name !== undefined}
        helperText={errors.name && errors.name.message}
        InputLabelProps={{ shrink: true }}
      />
      <TextField
        select
        {...register("categories_id")}
        value={watch("categories_id")}
        label="Categorias"
        margin={"normal"}
        variant={"outlined"}
        fullWidth
        onChange={(e) => {
          setValue("categories_id", e.target.value);
        }}
        // SelectProps={{
        //   multiple: true, nao rolou com o multiple =(
        // }}
        disabled={loading}
        error={errors.categories_id !== undefined}
        helperText={errors.categories_id && errors.categories_id.message}
        InputLabelProps={{ shrink: true }}
      >
        <MenuItem value="" disabled>
          <em>Selecione categorias</em>
        </MenuItem>
        {categories.map((category, key) => (
          <MenuItem key={key} value={category.id}>
            {category.name}
          </MenuItem>
        ))}
      </TextField>
      <Box dir={"rtl"}>
        <Button {...buttonProps} onClick={() => onSubmit(getValues(), null)}>
          Salvar
        </Button>
        <Button {...buttonProps} type="submit">
          Salvar e continuar editando
        </Button>
      </Box>
    </form>
  );
};
