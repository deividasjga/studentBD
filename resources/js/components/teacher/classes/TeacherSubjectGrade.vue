<template>
    <div>
      <h2>User ID: {{ user_id }}</h2>
      <h2>Class ID: {{ class_id }}</h2>
      <h2>Subject ID: {{ subject_id }}</h2>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      user_id: {
        type: Number,
        required: true
      },
      class_id: {
        type: Number,
        required: true
      },
      subject_id: {
        type: Number,
        required: true
      }
    }
  };
  </script>
  