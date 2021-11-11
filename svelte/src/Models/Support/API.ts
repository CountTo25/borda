import axios from "axios";
import env from "../../env";

const config = {
  baseURL: env.modelRoute,
  headers: {
    'Content-Type': 'application/json',
  },
};

const api = axios.create(config);

export default api;