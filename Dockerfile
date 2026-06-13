FROM node:18-alpine
WORKDIR /app

ENV NODE_ENV=production NEXT_TELEMETRY_DISABLED=1 PORT=3000

COPY next-dashboard/package*.json ./
RUN npm install

COPY next-dashboard/ ./
RUN npm run build

EXPOSE 3000
CMD ["npx", "next", "start", "-p", "3000"]