<template>
    <div>
        <div class="add">
            <input class="taskList" type="text" v-model="newTask" placeholder="Title">
            <textarea v-model="newTaskDesc" placeholder="Description"></textarea>
            <span class="input-group-button">
                <button class="button" @click="addTask">
                    Add task
                </button>
            </span>
        </div>

        <div class="list">
            <li v-for="(task, index) in tasks" :task="task" :key="index">
                <label>
                  {{task.title}}
                </label>
                <label>
                  {{task.description}}
                </label>
                <select v-model="task.status">
                  <option value="1">Created</option>
                  <option value="2">Finished</option>
                  <option value="3">Working</option>
                  <option value="4">Cancel Task</option>
                  <option value="5">Delete</option>
                </select>
            </li>
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
    update(id, title) {
      window.axios.post(`/api/cruds/update/${id}`, { title }).then(() => {
        this.tasks.find(task => task.id === id).edit = false;
        console.log('Success');
      });
    },
    del(id) {
      window.axios.post(`/api/cruds/delete/${id}`).then(() => {
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
