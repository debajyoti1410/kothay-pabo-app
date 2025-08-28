/**** SEND API REQUEST ****/
import useAuth from "@/store/useAuth";
import useUserStore from "@/store/useUserStore";
import axios, { AxiosError } from "axios";
import { useRouter } from "vue-router";

const useHttpRequest = () => {
  const loader = ref(false);
  const initialLoading = ref(true);
  const loading = ref(false);
  const auth = useAuth();
  const userStore = useUserStore();
  const router = useRouter();

  if (auth.getToken != 0) {
    axios.defaults.headers.common["Authorization"] = "Bearer " + auth.getToken;
  }

  const get = async (
    path = "",
    params = null,
    isLoader = true,
    callback = null
  ) => {
    try {
      if (isLoader) {
        loader.value = isLoader;
      }
      const response = await axios.get(path, { params: params });
      if (isLoader) {
        loader.value = !isLoader;
      }
      initialLoading.value = false;

      if (typeof callback === "function") {
        callback(null, response);
      }

      if (response.status == 200) {
        return {
          status: response.status,
          message: response.data.message,
          data: response.data.data ?? null,
          errors: []
        };
      }
      return [];
    } catch (error) {
      loader.value = false;
      return handleError(error, [], callback, false);
    }
  };

  const post = async (path = "", data, isLoader = true,config = null, callback = null) => {
    try {
      if (isLoader) {
        loading.value = isLoader;
      }
      const response = await axios.post(path, data, config);
      if (isLoader) {
        loading.value = !isLoader;
      }

      if (typeof callback === "function") {
        callback(null, response);
      }

      if (response.status == 200) {
        return {
          status: response.status,
          message: response.data.message,
          data: response.data.data ?? null,
          errors: []
        };
      }
      return null;
    } catch (error) {
      loading.value = false;
      return handleError(error, null, callback);
    }
  };

  const search = async (path = "", params = null, callback = null) => {
    try {
      const response = await axios.get(path, { params: params });
      if (typeof callback === "function") {
        callback(null, response);
      }

      if (response.data) {
        return response;
      }
      return [];
    } catch (error) {
      return handleError(error, [], callback, false);
    }
  };

  const handleError = (error, returnValue, callback) => {
    if (error instanceof AxiosError) {
      const errorResponse = error.response;
      const errorData = error.response.data;  
      
      if (
        errorResponse.status == 401 &&
        errorData.message == "Unauthenticated"
      ) {        
        userStore.setUser(null);
        auth.removeToken();
        router.push({ name: "login" });
        return;
      }

      if (typeof callback === "function") {
        callback(error);
      }

      return {
        status: errorResponse.status,
        message: errorData.message,
        errors: errorData.errors,
        data: errorData?.data ?? null,
      };
    }

    return error.response;
  };

  return {
    loader,
    initialLoading,
    loading,

    get,
    post,
    search,
  };
};
export default useHttpRequest;
