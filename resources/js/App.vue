<template>
    <div class="custom-form-container">
        <div class="custom-form-wrapper">
            <h1 class="custom-form-title">Origami Entities</h1>
            <ul class="custom-error-list" v-if="typeof errors === 'object'">
                <li v-for="(value, index) in errors" :key="index">{{ value[0] }}</li>
            </ul>
            <p class="custom-error-message" v-if="typeof errors === 'string'">{{ errors }}</p>
            <form method="post" @submit.prevent="handleSend">
                <div class="custom-form-group">
                    <label class="custom-label" for="entity_data_name">Entity data name</label>
                    <input class="custom-input" id="entity_data_name" type="text" v-model="form.entity_data_name" required />
                </div>
                <div class="custom-form-group">
                    <label class="custom-label" for="action_type">Action type</label>
                    <select class="custom-input" v-model="form.action_type">
                        <option value="add">Add</option>
                        <option value="edit">Edit</option>
                    </select>
                </div>
                <div class="custom-form-group" v-if="form.action_type === 'edit'">
                    <label class="custom-label" for="entity_id">Entity Id</label>
                    <input class="custom-input" id="entity_id" type="text" v-model="form.entity_id" required />
                </div>
                <div v-if="form.action_type === 'edit'">
                    <h2 class="custom-subtitle">Edit Data</h2>
                    <div class="custom-form-group" v-for="(editField, index) in form.edit_data" :key="index">
                        <label class="custom-label" :for="'edit_field_name_' + index">Field Name</label>
                        <input
                            class="custom-input"
                            :id="'edit_field_name_' + index"
                            type="text"
                            v-model="editField[0]"
                            @input="(event) => updateEditFieldName(index, event.target.value)"
                            required
                        />
                        <label class="custom-label" :for="'edit_field_value_' + index">Field Value</label>
                        <input
                            class="custom-input"
                            :id="'edit_field_value_' + index"
                            type="text"
                            v-model="editField[1]"
                            @input="(event) => updateEditFieldValue(index, event.target.value)"
                            required
                        />
                        <div style="display: flex; justify-content: space-between">
                            <button style="color: #56d329" class="custom-add-button" @click="addEditField" type="button">Add Field</button>
                            <button style="color: #ec3333" class="custom-remove-button" @click="removeEditField(index)" type="button">Remove Field</button>
                        </div>
                    </div>
                </div>
                <div v-if="form.action_type === 'add'">
                    <div class="custom-form-group" v-for="(formData, formDataIndex) in form.form_data" :key="formDataIndex">
                        <label class="custom-label" :for="'group_data_name_' + formDataIndex">Group Data Name</label>
                        <input class="custom-input" :id="'group_data_name_' + formDataIndex" type="text" v-model="formData.group_data_name" required />
                        <div style="background-color: beige; padding: 10px; margin: 5px" class="custom-form-group" v-for="(data, dataIndex) in formData.data" :key="dataIndex">
                            <label class="custom-label" :for="'fld_' + formDataIndex + '_' + dataIndex">Field Name</label>
                            <input
                                class="custom-input"
                                :id="'fld_' + formDataIndex + '_' + dataIndex"
                                type="text"
                                :value="data.field_name"
                                @input="(event) => updateFieldName(formDataIndex, dataIndex, event.target.value)"
                                required
                            />
                            <label class="custom-label" :for="'value_' + formDataIndex + '_' + dataIndex">Field Value Type</label>
                            <select class="custom-input" :id="'value_' + formDataIndex + '_' + dataIndex" v-model="data.value_type" @change="updateValueType(formDataIndex, dataIndex)">
                                <option value="string">String</option>
                                <option value="array">Array</option>
                                <option value="object">Object</option>
                            </select>
                            <div style="display: flex; justify-content: space-between">
                                <div v-if="data.value_type === 'string'">
                                    <label class="custom-label" :for="'value_string_' + formDataIndex + '_' + dataIndex">Field Value</label>
                                    <input
                                        class="custom-input"
                                        :id="'value_string_' + formDataIndex + '_' + dataIndex"
                                        type="text"
                                        :value="data.value"
                                        @input="(event) => updateFieldValue(formDataIndex, dataIndex, event.target.value)"
                                        required
                                    />
                                </div>
                                <div v-else-if="data.value_type === 'array'">
                                    <label class="custom-label" :for="'value_array_' + formDataIndex + '_' + dataIndex">Array Values</label>
                                    <div v-for="(arrayValue, arrayIndex) in data.value" :key="arrayIndex">
                                        <input
                                            class="custom-input"
                                            :id="'value_array_' + formDataIndex + '_' + dataIndex + '_' + arrayIndex"
                                            type="text"
                                            :value="arrayValue"
                                            @input="(event) => updateArrayValue(formDataIndex, dataIndex, arrayIndex, event.target.value)"
                                            required
                                        />
                                        <div style="display: flex; justify-content: space-between">
                                            <button class="custom-add-button" @click="addArrayValue(formDataIndex, dataIndex)" type="button">Add Value</button>
                                            <button class="custom-remove-button" @click="removeArrayValue(formDataIndex, dataIndex, arrayIndex)" type="button">Remove Value</button>
                                        </div>
                                    </div>
                                </div>
                                <div v-else-if="data.value_type === 'object'">
                                    <label class="custom-label" :for="'value_object_name_' + formDataIndex + '_' + dataIndex">Object Name</label>
                                    <input
                                        class="custom-input"
                                        :id="'value_object_name_' + formDataIndex + '_' + dataIndex"
                                        type="text"
                                        :value="data.value.name"
                                        @input="(event) => updateObjectValueName(formDataIndex, dataIndex, event.target.value)"
                                        required
                                    />
                                    <label class="custom-label" :for="'value_object_value_' + formDataIndex + '_' + dataIndex">Object Value</label>
                                    <input
                                        class="custom-input"
                                        :id="'value_object_value_' + formDataIndex + '_' + dataIndex"
                                        type="text"
                                        :value="data.value.value"
                                        @input="(event) => updateObjectValueValue(formDataIndex, dataIndex, event.target.value)"
                                        required
                                    />
                                </div>
                                <button style="color: #ec3333" class="custom-remove-button" @click="removeDataField(formDataIndex, dataIndex)" type="button">Remove Field</button>
                            </div>
                        </div>
                        <button style="color: #56d329; margin-bottom: 20px" class="custom-add-button" @click="addDataField(formDataIndex)" type="button">Add Field</button>
                        <div style="display: flex; justify-content: space-between">
                            <button style="color: #56d329" class="custom-add-button" @click="addFormData" type="button">Add Form Data</button>
                            <button style="color: #ec3333" class="custom-remove-button" @click="removeFormData(formDataIndex)" type="button">Remove Form Data</button>
                        </div>
                    </div>
                </div>
                <div class="custom-form-group">
                    <button class="custom-submit-button" type="submit">Send</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import {onMounted, ref} from "vue";
import { apiService } from './apiService.js';

//we use only the App.vue component for all the logic and styles for ease of implementation within the test framework, because according to the assignment we only need one form without anything else

//for more complex tasks we should use a router, vuex, tailwindcss(or other)/custom styles and more pages and components

export default {
    setup() {
        const errors = ref();
        const form = ref({
            entity_data_name: "",
            action_type: "",
            form_data: [
                {
                    group_data_name: "",
                    data: [{ field_name: "", value: "", value_type: "string" }],
                },
            ],
            edit_data: [
                ["",""]
            ],
        });

        const getUrlParams = () => {
            const searchParams = new URLSearchParams(window.location.search);
            form.value.entity_data_name = searchParams.get("entity_data_name") || "";
            form.value.action_type = searchParams.get("action_type") || "";

            if (searchParams.get("action_type") === 'edit') {
                form.value.entity_id = searchParams.get("entity_id");
            }
        };

        const addFormData = () => {
            form.value.form_data.push({
                group_data_name: "",
                data: [{ field_name: "", value: "", value_type: "string" }],
            });
        };

        const removeFormData = (index) => {
            form.value.form_data.splice(index, 1);
        };

        const addDataField = (formDataIndex) => {
            form.value.form_data[formDataIndex].data.push({ field_name: "", value: "", value_type: "string" });
        };

        const removeDataField = (formDataIndex, dataIndex) => {
            form.value.form_data[formDataIndex].data.splice(dataIndex, 1);
        };

        const addArrayValue = (formDataIndex, dataIndex) => {
            form.value.form_data[formDataIndex].data[dataIndex].value.push("");
        };

        const removeArrayValue = (formDataIndex, dataIndex, arrayIndex) => {
            form.value.form_data[formDataIndex].data[dataIndex].value.splice(arrayIndex, 1);
        };

        const updateFieldName = (formDataIndex, dataIndex, value) => {
            form.value.form_data[formDataIndex].data[dataIndex].field_name = value;
        };

        const updateValueType = (formDataIndex, dataIndex) => {
            const value_type = form.value.form_data[formDataIndex].data[dataIndex].value_type;
            if (value_type === "array") {
                form.value.form_data[formDataIndex].data[dataIndex].value = [""];
            } else if (value_type === "object") {
                form.value.form_data[formDataIndex].data[dataIndex].value = { name: "", value: "" };
            } else {
                form.value.form_data[formDataIndex].data[dataIndex].value = "";
            }
        };

        const updateFieldValue = (formDataIndex, dataIndex, value) => {
            form.value.form_data[formDataIndex].data[dataIndex].value = value;
        };

        const updateArrayValue = (formDataIndex, dataIndex, arrayIndex, value) => {
            form.value.form_data[formDataIndex].data[dataIndex].value[arrayIndex] = value;
        };

        const updateObjectValueName = (formDataIndex, dataIndex, value) => {
            form.value.form_data[formDataIndex].data[dataIndex].value.name = value;
        };

        const updateObjectValueValue = (formDataIndex, dataIndex, value) => {
            form.value.form_data[formDataIndex].data[dataIndex].value.value = value;
        };

        const addEditField = () => {
            form.value.edit_data.push(["", ""]); // Инициализация нового поля
        };

        const removeEditField = (index) => {
            form.value.edit_data.splice(index, 1);
        };

        const updateEditFieldName = (index, value) => {
            form.value.edit_data[index][0] = value;
        };

        const updateEditFieldValue = (index, value) => {
            form.value.edit_data[index][1] = value;
        };

        onMounted(() => {
            getUrlParams();
        });

        const actionTypes = {
            add: 'addEntity',
            edit: 'editEntity',
        };

        const handleSend = async () => {
            try {
                if (actionTypes[form.value.action_type]) {
                    await apiService[actionTypes[form.value.action_type]](form.value);
                } else {
                    console.error('Invalid actionType');
                }
            } catch (e) {
                if (e && e.response && e.response.data && e.response.data.errors) {
                    errors.value = Object.values(e.response.data.errors);
                } else {
                    errors.value = e.response.data.message || "";
                }
            }
        };

        return {
            form,
            errors,
            handleSend,
            addFormData,
            removeFormData,
            addDataField,
            removeDataField,
            addArrayValue,
            removeArrayValue,
            updateFieldName,
            updateValueType,
            updateFieldValue,
            updateArrayValue,
            updateObjectValueName,
            updateObjectValueValue,
            addEditField,
            removeEditField,
            updateEditFieldName,
            updateEditFieldValue,
        };
    }
};
</script>

<style scoped>
.custom-form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.custom-form-wrapper {
    background-color: #ffffff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    border-radius: 8px;
}

.custom-form-title {
    color: #333333;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
}

.custom-error-list {
    color: #ff0000;
    list-style-type: disc;
    margin-left: 20px;
}

.custom-error-message {
    color: #ff0000;
    margin-top: 10px;
}

.custom-form-group {
    margin-bottom: 20px;
}

.custom-label {
    color: #555555;
    font-size: 14px;
    margin-bottom: 5px;
}

.custom-input {
    width: 100%;
    padding: 10px;
    border: 1px solid #cccccc;
    border-radius: 4px;
    font-size: 16px;
}

.custom-submit-button {
    background-color: #3490dc;
    color: #ffffff;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.custom-submit-button:hover {
    background-color: #2779bd;
}
</style>
