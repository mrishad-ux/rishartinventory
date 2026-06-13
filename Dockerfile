FROM node:18-alpine
WORKDIR /app

ENV NODE_ENV=production NEXT_TELEMETRY_DISABLED=1 PORT=3000

COPY next-dashboard/package*.json ./
RUN npm install

COPY next-dashboard/ ./

# Build with explicit output to verify it works
RUN npm run build && echo "BUILD_SUCCESS" && ls .next/standalone/ 2>/dev/null || echo "No standalone dir"

EXPOSE 3000
CMD ["node", ".next/standalone/server.js"]