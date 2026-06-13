FROM node:18-alpine
WORKDIR /app

ENV NODE_ENV=production NEXT_TELEMETRY_DISABLED=1 PORT=3000

COPY next-dashboard/package*.json ./

# Debug install
RUN npm install 2>&1
RUN echo "=== Install done ===" && ls node_modules/ | wc -l

COPY next-dashboard/ ./

# Debug build with full output
RUN echo "=== Build starting ===" && npm run build 2>&1 | tail -50

EXPOSE 3000
CMD ["npx", "next", "start", "-p", "3000"]