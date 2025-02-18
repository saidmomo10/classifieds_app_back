import { ref } from 'vue';
import axios from 'axios';

const backendUrl = import.meta.env.VITE_BACKEND_URL;
export function useCategories() {
    const token = localStorage.getItem('token');

    const clientHttp = axios.create({
        baseURL: `${backendUrl}/api/`,
        headers: {
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
        }
    });

    const categoryData = ref([]);

    const status = async () => {
        try {
            const statusResponse = await clientHttp.get('subcategories');
            console.log(statusResponse);

            if (statusResponse.status === 200) {
                categoryData.value = statusResponse.data;
            }
        } catch (error) {
            console.log(error);
        }
    }

    const category = ref('');

    const cat = async () => {
        try {
            const statusResponse = await clientHttp.get('categories');
            console.log(statusResponse);

            if (statusResponse.status === 200) {
                category.value = statusResponse.data;
            }
        } catch (error) {
            console.log(error);
        }
    }

    const statusData = ref([]);

    const showCategory = async (id) => {
        try {
            const statusResponse = await clientHttp.get(`subcategories/${id}`);
            console.log(statusResponse);

            if (statusResponse.status === 200) {
                statusData.value = statusResponse.data;            
            }
        } catch (error) {
            console.log(error);
        }
    }

    const createSubcategory = async (categoryData) => {
        try {
            const user = await clientHttp.post('subcategories', categoryData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    Authorization: `Bearer ${token}`,
                }
            });
            console.log(user);
        } catch (error) {
            console.log(error);
        }
    }

    return {
        showCategory,
        status,
        statusData,
        categoryData,
        createSubcategory,
        cat,
        category
    }
}