<template>
    <div>
        <div class="add">
            <input class="taskList" type="text" v-model="newTask" placeholder="New task">
            <span class="input-group-button">
                <button class="button" @click="addTask">
                    Add
                </button>
            </span>
        </div>

        <div class="list">
            <li v-for="(task, index) in tasks" :task="task" :key="index">
                <label :class="{ 'done': task.completed, 'notDone': !task.completed }" v-if="task.edit == false">
                  <input v-model="task.completed" type="checkbox" @click="updateDone(task.id, task.completed)"> {{task.title}}
                </label>
                <input class="taskList" v-if="task.edit == true" type="text" v-model="task.title"/>
                <button class="button" @click="task.edit = true" v-if="task.edit == false">Edit</button>
                <button class="button" @click="update(task.id, task.title)" v-if="task.edit == true">Save</button>
                <button class="button" @click="del(task.id)">Delete</button>
            </li>
        </div>
    </div>
</template>

<script>
Vue.prototype.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');

export default {
  data () {
    return {
        newTask: '',
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
            completed: crud.isDone,
            edit: false
          });
        });
      });
    },
    addTask() {
      if (this.newTask) {
        window.axios.post('/api/cruds/create', {
          title: this.newTask,
          completed: false,
          userId: this.$userId
        }).then((response) => {
          this.read();
          console.log('Success');
        });
      }
    },
    update(id, title) {
      window.axios.post(`/api/cruds/update/${id}`, { title }).then(() => {
        this.tasks.find(task => task.id === id).edit = false;
        console.log('Success');
      });
    },
    updateDone(id, completed) {
      window.axios.post(`/api/cruds/updateDone/${id}`, { completed: !completed }).then(() => {
        this.tasks.find(task => task.id === id).completed = !completed;
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
