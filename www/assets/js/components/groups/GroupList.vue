<script setup>
const items = [
  {
    id: 1,
    title: 'Музыка и клипы',
    image: 'https://via.placeholder.com/150', // Замените на URL вашей картинки
  },
  {
    id: 2,
    title: 'Новости IT и технологий',
    image: 'https://via.placeholder.com/150', // Замените на URL вашей картинки
  },
  {
    id: 3,
    title: 'Путешествия по миру',
    image: 'https://via.placeholder.com/150', // Замените на URL вашей картинки
  },
  {
    id: 4,
    title: 'Рецепты и кулинария',
    image: 'https://via.placeholder.com/150', // Замените на URL вашей картинки
  },
  {
    id: 5,
    title: 'Фильмы и сериалы',
    image: 'https://via.placeholder.com/150', // Замените на URL вашей картинки
  },
  {
    id: 6,
    title: 'Здоровье и фитнес',
    image: 'https://via.placeholder.com/150', // Замените на URL вашей картинки
  },
  {
    id: 7,
    title: 'Автомобили и мотоциклы',
    image: 'https://via.placeholder.com/150', // Замените на URL вашей картинки
  },
  {
    id: 8,
    title: 'Юмор и развлечения',
    image: 'https://via.placeholder.com/150', // Замените на URL вашей картинки
  },
  {
    id: 9,
    title: 'Фотография и искусство',
    image: 'https://via.placeholder.com/150', // Замените на URL вашей картинки
  },
  {
    id: 10,
    title: 'Наука и образование',
    image: 'https://via.placeholder.com/150', // Замените на URL вашей картинки
  },
];

const user = {
  name: 'Иван Иванов',
  avatar: 'https://via.placeholder.com/100', // Замените на URL вашей аватарки
};
</script>

<template>
  <div class="user-container">
    <img :src="user.avatar" alt="User Avatar" class="user-avatar"/>
    <span class="user-name">{{ user.name }}</span>
  </div>
  <div class="grid-container">
    <div v-for="item in items" :key="item.id" class="grid-item">
      <img :src="item.image" alt="Item Image" class="item-image"/>
      <h3>{{ item.title }}</h3>
    </div>
  </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

body {
  font-family: 'Roboto', sans-serif;
}

.user-container {
  display: flex;
  align-items: center;
  margin: 20px 0 0 20px; /* Отступы сверху и слева */
}

.user-avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  margin-right: 10px;
}

.user-name {
  font-size: 1.2em;
  font-weight: bold;
}

.grid-container {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 10px; /* Задайте промежуток между элементами по необходимости */
  background-color: #f9f9f9; /* Цвет фона для контейнера */
  margin: 20px; /* Отступы сверху и по бокам */
  padding: 20px; /* Внутренние отступы */
  border-radius: 10px; /* Закругленные углы */
}

.grid-item {
  background-color: white;
  padding: 12px;
  text-align: center;
  border-radius: 12px; /* Закругленные углы */
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 160px;
  height: 270px;
}

.item-image {
  width: 162.5px;
  height: 170px;
  object-fit: cover;
  margin-bottom: 10px;
  border-radius: 8px; /* Закругленные углы для картинки */
}

h3 {
  margin: 0;
  font-size: 20px; /* Размер шрифта */
}
</style>