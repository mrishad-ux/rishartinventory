FROM node:18-alpine
WORKDIR /app
ENV NODE_ENV=production NEXT_TELEMETRY_DISABLED=1 PORT=3000

COPY next-dashboard/package*.json ./
RUN npm install

COPY next-dashboard/ ./
RUN npm run build

EXPOSE 3000

# Run the built Next.js server directly
CMD ["node_modules/.bin/next", "start", "-p", "3000"]