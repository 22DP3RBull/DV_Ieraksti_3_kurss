<template>
  <div class="container">
    <!-- Header -->
    <header>
      <button @click="goToMainPage" class="btn">Main Page</button>
      <h1>Admin Dashboard</h1>
    </header>

    <!-- Add Student Button -->
    <div class="add-student">
      <button @click="showAddStudentPopup" class="btn">
        <img src="https://img.icons8.com/ios-filled/50/000000/plus.png" alt="Add Student" />
      </button>
    </div>

    <!-- Main Content -->
    <main>
      <p>Welcome to the admin dashboard.</p>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Surname</th>
            <th>Room</th>
            <th>Floor</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Checked In</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="student in students" :key="student.id">
            <td>{{ student.name }}</td>
            <td>{{ student.surname }}</td>
            <td>{{ student.room }}</td>
            <td>{{ student.floor }}</td>
            <td>{{ student.phone }}</td>
            <td>{{ student.email }}</td>
            <td>{{ student.checkedIn ? 'Yes' : 'No' }}</td>
            <td>
              <button @click="showEditStudentPopup(student)" class="btn">Edit</button>
              <button @click="deleteStudent(student.id)" class="btn">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </main>

    <!-- Footer -->
    <footer>
      <p>Contact: admin@example.com</p>
    </footer>

    <!-- Add/Edit Student Popup -->
    <div v-if="showPopup" class="popup">
      <div class="popup-content">
        <h2>{{ isEditing ? 'Edit Student' : 'Add Student' }}</h2>
        <form @submit.prevent="isEditing ? updateStudent() : addStudent()">
          <div>
            <label for="name">Name:</label>
            <input type="text" id="name" v-model="studentForm.name" required>
          </div>
          <div>
            <label for="surname">Surname:</label>
            <input type="text" id="surname" v-model="studentForm.surname" required>
          </div>
          <div>
            <label for="room">Room:</label>
            <input type="text" id="room" v-model="studentForm.room" required>
          </div>
          <div>
            <label for="floor">Floor:</label>
            <input type="text" id="floor" v-model="studentForm.floor" required>
          </div>
          <div>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" v-model="studentForm.phone" required>
          </div>
          <div>
            <label for="email">Email:</label>
            <input type="email" id="email" v-model="studentForm.email" required>
          </div>
          <div>
            <label for="checkedIn">Checked In:</label>
            <input type="checkbox" id="checkedIn" v-model="studentForm.checkedIn">
          </div>
          <button type="submit" class="btn">{{ isEditing ? 'Update' : 'Add' }}</button>
          <button @click="closePopup" class="btn">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AdminDashboard',
  data() {
    return {
      students: [],
      showPopup: false,
      isEditing: false,
      studentForm: {
        id: null,
        name: '',
        surname: '',
        room: '',
        floor: '',
        phone: '',
        email: '',
        checkedIn: false
      }
    }
  },
  methods: {
    goToMainPage() {
      window.location.href = '/';
    },
    showAddStudentPopup() {
      this.isEditing = false;
      this.resetForm();
      this.showPopup = true;
    },
    showEditStudentPopup(student) {
      this.isEditing = true;
      this.studentForm = { ...student };
      this.showPopup = true;
    },
    closePopup() {
      this.showPopup = false;
    },
    resetForm() {
      this.studentForm = {
        id: null,
        name: '',
        surname: '',
        room: '',
        floor: '',
        phone: '',
        email: '',
        checkedIn: false
      };
    },
    async fetchStudents() {
      try {
        const response = await axios.get('/api/students');
        this.students = response.data;
      } catch (error) {
        console.error('Error fetching students:', error);
      }
    },
    async addStudent() {
      try {
        await axios.post('/api/students', this.studentForm);
        this.fetchStudents();
        this.closePopup();
      } catch (error) {
        console.error('Error adding student:', error);
      }
    },
    async updateStudent() {
      try {
        await axios.put(`/api/students/${this.studentForm.id}`, this.studentForm);
        this.fetchStudents();
        this.closePopup();
      } catch (error) {
        console.error('Error updating student:', error);
      }
    },
    async deleteStudent(id) {
      try {
        await axios.delete(`/api/students/${id}`);
        this.fetchStudents();
      } catch (error) {
        console.error('Error deleting student:', error);
      }
    }
  },
  mounted() {
    this.fetchStudents();
  }
}
</script>

<style scoped src="../../css/admin-dashboard.css"></style>
