FROM golang:1.22.2-alpine AS builder


# Устанавливаем рабочую директорию внутри контейнера
WORKDIR /app

# Копируем исходники в рабочую директорию
COPY src/ ./src/

# Переходим в директорию с main.go
WORKDIR /app/src/cmd/service

# Компилируем Go-приложение
RUN go build -o /app/service main.go

# Используем минимальный образ для финального контейнера
FROM alpine:latest

# Устанавливаем рабочую директорию внутри контейнера
WORKDIR /root/

# Копируем скомпилированный бинарник из предыдущего этапа
COPY --from=builder /app/service .

# Указываем команду для запуска приложения
CMD ["./service"]

