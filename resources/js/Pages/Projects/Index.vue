<template>
    <AppLayout title="Projects">
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Projects
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="p-6 overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg"
                >
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <h3
                                class="text-lg font-medium text-gray-900 dark:text-gray-100"
                            >
                                Project List
                            </h3>
                        </div>
                        <PrimaryButton
                            @click="createProject"
                            class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md dark:bg-gray-200 dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                        >
                            Create Project
                        </PrimaryButton>
                    </div>

                    <!-- Dropdown for Status Filter -->
                    <div class="relative inline-block mb-4 text-left">
                        <button
                            @click="toggleDropdown"
                            class="inline-flex items-center px-4 py-2 text-xs font-semibold bg-gray-100 border rounded-md btn hover:bg-gray-200"
                        >
                            Status: {{ (currentFilters?.status) ? formatStatus(currentFilters?.status) : 'All' }}
                            <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="inline-block w-5 h-5 ml-2"
                            >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 9l6 6 6-6"
                            />
                            </svg>
                        </button>
                        <div
                            v-if="isDropdownOpen"
                            class="absolute left-0 z-10 w-40 mt-2 bg-white border border-gray-300 rounded shadow-md"
                        >
                            <ul>
                                <li
                                    class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                                    @click="selectStatus('all')"
                                >
                                    All
                                </li>
                                <li
                                    v-for="status in statuses"
                                    :key="status"
                                    class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                                    @click="selectStatus(status)"
                                >
                                    {{ formatStatus(status) || 'All' }}
                                </li>
                            </ul>
                        </div>
                        <div>
                            <TextInput
                                id="search"
                                type="search"
                                class="block w-full mt-1"
                                v-model="searchTerm"
                                required
                                placeholder="Search Title"
                            />
                        </div>
                    </div>

                    <div v-if="projects.length === 0" class="py-12 text-center">
                        <div class="mb-2 text-gray-500 dark:text-gray-400">
                            No projects found
                        </div>
                        <div class="text-sm text-gray-400 dark:text-gray-500">
                            Get started by creating your first project
                        </div>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table
                            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                        >
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300"
                                    >
                                        Title
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300"
                                    >
                                        Due Date
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300"
                                    >
                                        Tasks
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-300"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700"
                            >
                                <tr
                                    v-for="project in filteredProjects"
                                    :key="project.id"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                        >
                                            {{ project.title }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{ project.description }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full"
                                            :class="{
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100':
                                                    project.status ===
                                                    'not_started',
                                                'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100':
                                                    project.status ===
                                                    'in_progress',
                                                'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100':
                                                    project.status ===
                                                    'completed',
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300':
                                                    project.status ===
                                                    'on_hold',
                                            }"
                                        >
                                            {{ formatStatus(project.status) }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        {{ formatDate(project.due_date) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        {{ project.tasks_count }} tasks
                                    </td>
                                    <td
                                        class="px-6 py-4 space-x-3 text-sm font-medium text-right whitespace-nowrap"
                                    >
                                        <Link
                                            :href="
                                                route(
                                                    'projects.show',
                                                    project.id
                                                )
                                            "
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700"
                                        >
                                            View
                                        </Link>
                                        <button
                                            @click="editProject(project)"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="deleteProject(project)"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-red-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-red-400 dark:bg-gray-800 hover:text-red-700 dark:hover:text-red-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Project Modal -->
        <Modal :show="showModal" @close="closeModal">
            <div class="p-6">
                <h2
                    class="text-lg font-medium text-gray-900 dark:text-gray-100"
                >
                    {{ form.id ? "Edit Project" : "Create Project" }}
                </h2>

                <div class="mt-6">
                    <form @submit.prevent="submitForm">
                        <div>
                            <InputLabel for="title" value="Title" />
                            <TextInput
                                id="title"
                                type="text"
                                class="block w-full mt-1"
                                v-model="form.title"
                                required
                            />
                            <InputError
                                :message="form.errors.title"
                                class="mt-2"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="description" value="Description" />
                            <textarea
                                id="description"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                v-model="form.description"
                                rows="3"
                            ></textarea>
                            <InputError
                                :message="form.errors.description"
                                class="mt-2"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="status" value="Status" />
                            <select
                                id="status"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                v-model="form.status"
                                required
                            >
                                <option value="not_started">Not Started</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="on_hold">On Hold</option>
                            </select>
                            <InputError
                                :message="form.errors.status"
                                class="mt-2"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="due_date" value="Due Date" />
                            <TextInput
                                id="due_date"
                                type="date"
                                class="block w-full mt-1"
                                v-model="form.due_date"
                                required
                            />
                            <InputError
                                :message="form.errors.due_date"
                                class="mt-2"
                            />
                        </div>

                        <div class="flex justify-end mt-6">
                            <SecondaryButton @click="closeModal" class="mr-3">
                                Cancel
                            </SecondaryButton>
                            <PrimaryButton :disabled="form.processing">
                                {{ form.id ? "Update" : "Create" }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { router } from "@inertiajs/vue3";
import { debounce } from "lodash";

const props = defineProps({
    projects: {
        type: Array,
        required: true,
    },
    filters: Object,
});

const statuses = ref(['not_started','in_progress','completed','on_hold']);
const isDropdownOpen = ref(false); // To toggle the dropdown visibility

const currentFilters = computed(() => props.filters);
const searchTerm = ref("");

const filteredProjects = computed(() => {
    return props.projects.filter((item) => item.title.toLowerCase().includes(searchTerm.value.toLowerCase()));
});

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

const selectStatus = (status) => {
  isDropdownOpen.value = false; // Close the dropdown after selection
  router.get(route("projects.index"), { status }, { preserveState: true });
};


const showModal = ref(false);
const form = useForm({
    id: null,
    title: "",
    description: "",
    status: "not_started",
    due_date: "",
});

const createProject = () => {
    form.reset();
    showModal.value = true;
};

const editProject = (project) => {
    form.id = project.id;
    form.title = project.title;
    form.description = project.description;
    form.status = project.status;
    form.due_date = project.due_date;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
};

const submitForm = () => {
    if (form.id) {
        form.put(route("projects.update", form.id), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route("projects.store"), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

const deleteProject = (project) => {
    if (confirm("Are you sure you want to delete this project?")) {
        form.delete(route("projects.destroy", project.id), {
            preserveScroll: true,
        });
    }
};

const formatStatus = (status) => {
    return status
        .split("_")
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ");
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
};
</script>
