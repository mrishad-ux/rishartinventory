FROM node:18-alpine
WORKDIR /app

COPY next-dashboard/package*.json ./
RUN npm install

COPY next-dashboard/ ./
RUN npm run build

EXPOSE 3000
ENV NODE_ENV=production NEXT_TELEMETRY_DISABLED=1 PORT=3000
CMD ["npm", "start"]
