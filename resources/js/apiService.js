import axios from 'axios';

const BASE_URL = 'http://localhost:8000/api';

export const apiService = {
    async addEntity(data) {

        const requestData = {
            'entity_data_name': data.entity_data_name,
            'entityFormData': data.form_data.map(entityItem => {
                return {
                    'group_data_name': entityItem.group_data_name,
                    'data': entityItem.data.reduce((acc, field) => {
                        acc[field.field_name] = field.value;
                        return acc;
                    }, {})
                };
            })
        };

        try {
            const response = await axios.post(`${BASE_URL}/entities`, requestData);
            console.log('Entity added successfully', response.data);
            return response.data;
        } catch (error) {
            console.error('Error adding entity', error);
        }
    },
    async editEntity(data) {
        const requestData = {
            'entity_data_name': data.entity_data_name,
            'fields': data.edit_data
        };

        try {
            const response = await axios.put(`${BASE_URL}/entities/` + data.entity_id, requestData);
            console.log('Entity updated successfully', response.data);
            return response.data;
        } catch (error) {
            console.error('Error adding entity', error);
        }
    },
};
