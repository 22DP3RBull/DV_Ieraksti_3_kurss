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
          <button type="submit" class="btn">{{ isEditing ? 'Update' : 'Add' }}</button>
          <button @click="closePopup" class="btn">Cancel</button>
        </form>
        <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
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
        email: ''
      },
      errorMessage: ''
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
      this.errorMessage = '';
    },
    resetForm() {
      this.studentForm = {
        id: null,
        name: '',
        surname: '',
        room: '',
        floor: '',
        phone: '',
        email: ''
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
      const namePattern = /^[a-zA-Zāčēģīķļņōŗšūž\s\-]+$/;
      const phonePattern = /^\d{8}$/;
      const emailPattern = /^[a-zA-Z0-9._%+-]+@rvt\.lv$/;
      const floorPattern = /^[1-5]$/;
      const roomPattern = /^(1[0-2][0-8]|2[0-2][0-8]|3[0-2][0-8]|4[0-2][0-8]|5[0-2][0-8])$/;

      if (!namePattern.test(this.studentForm.name)) {
        alert('Invalid name. Only letters, spaces, hyphens, and softening marks are allowed.');
        return;
      }
      if (!namePattern.test(this.studentForm.surname)) {
        alert('Invalid surname. Only letters, spaces, hyphens, and softening marks are allowed.');
        return;
      }
      if (!phonePattern.test(this.studentForm.phone)) {
        alert('Invalid phone number. It must be 8 digits.');
        return;
      }
      if (!emailPattern.test(this.studentForm.email)) {
        alert('Invalid email. It must end with @rvt.lv.');
        return;
      }
      if (!floorPattern.test(this.studentForm.floor)) {
        alert('Invalid floor. It must be between 1 and 5.');
        return;
      }
      if (!roomPattern.test(this.studentForm.room)) {
        alert('Invalid room. It must be between 101-128, 201-228, 301-328, 401-428, or 501-528.');
        return;
      }

      try {
        await axios.post('/api/students', this.studentForm);
        this.fetchStudents();
        this.closePopup();
      } catch (error) {
        console.error('Error adding student:', error);
        this.errorMessage = error.response?.data?.error || 'An error occurred while adding the student.';
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
