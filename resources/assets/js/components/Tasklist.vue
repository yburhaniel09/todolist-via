<template>
    <div>
        <div class="add">
            <input class="taskList" type="text" v-model="newTask" placeholder="Title"> <br>
            <textarea class="taskList" v-model="newTaskDesc" placeholder="Description"></textarea> <br>
            <span class="input-group-button">
                <button class="button" @click="addTask">
                    Add task
                </button>
            </span>
        </div>

        <div class="list">
          <table>
            <tr>
              <td style="width: 25%;"><b>Title</b></td>
              <td style="width: 45%;"><b>Description</b></td>
              <td style="width: 15%;"><b>Status</b></td>
              <td style="width: 15%;"><b>Action</b></td>
            </tr>
            <tr v-for="(task, index) in tasks" :task="task" :key="index">
              <td>
                {{task.title}}
              </td>
              <td>
                {{task.description}}
              </td>
              <td>
                <span v-if="task.status == 1"> Created </span>
                <span v-if="task.status == 2"> Finished </span>
                <span v-if="task.status == 3"> Working </span>
                <span v-if="task.status == 4"> Cancelled </span>
              </td>
              <td>
                <select v-model="status" @change="update($event, task.id)">
                  <option value="" disabled selected hidden>Choose action</option>
                  <option value="2">Finished</option>
                  <option value="3">Working</option>
                  <option value="4">Cancel Task</option>
                  <option value="5">Delete</option>
                </select>
              </td>
            </tr>
          </table>
        </div>
    </div>
</template>

<script>
import Vue from 'vue'; 
Vue.prototype.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');

export default {
  data () {
    return {
        newTask: '',
        newTaskDesc: '',
        tasks: []
    }
  },
  methods: {
    read() {
      window.axios.get(`/api/cruds/${this.$userId}`).then(({ data }) => {
        this.tasks = [];
        data.forEach(crud => {
          this.tasks.push({
            id: crud.id,
            title: crud.title,
            description: crud.description,
            status: crud.status
          });
        });
      });
    },
    addTask() {
      if (this.newTask) {
        window.axios.post('/api/cruds/create', {
          title: this.newTask,
          description: this.newTaskDesc,
          status: 1,
          userId: this.$userId
        }).then((response) => {
          this.newTask = '';
          this.newTaskDesc = '';
          this.read();
          console.log('Success');
        });
      }
      else {
        alert("Title is required");
      }
    },
    update(e, id) {
      if (e.target.value == 5){
        this.del(id);
      }
      else {
        this.updateStatus(e.target.value, id);
      }
    },
    updateStatus(status, id) {
      window.axios.put(`/api/cruds/update/${id}`, { status }).then(() => {
        this.tasks.find(task => task.id === id).status = status;
        console.log('Success');
      });
    },
    del(id) {
      window.axios.delete(`/api/cruds/delete/${id}`).then(() => {
        let index = this.tasks.findIndex(task => task.id === id);
        this.tasks.splice(index, 1);
        console.log('Success');
      });
    }
  },
  created() {
    this.read();
  }
}
</script>

<style scoped>
  div.add {
    text-align: center;
  }

  div.list {
    margin-top: 10px;
  }

  div.save {
    margin-top: 10px;
    text-align: center;
  }

  li {
    list-style-type: none;
  }

  .taskList {
    width: 550px;
  }

  .done {
    width: 550px;
    text-decoration: line-through;
    color: gray;
  }

   .notDone {
    width: 550px;
    text-decoration: none;
  }

  .button {
    width: 80px;
  }

  .savebutton {
    width: 120px;
  }


</style>
