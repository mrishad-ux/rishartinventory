FROM node:18-alpine
WORKDIR /app

# Debug: check what's in the repo
RUN ls -la

# Copy only package files first
COPY next-dashboard/package*.json ./

# Install with verbose output
RUN npm install --loglevel verbose 2>&1 | tail -20

# Check what was installed
RUN ls node_modules/ | head -20

COPY next-dashboard/ ./

# Run build with debug
RUN npm run build 2>&1 | tail -30

EXPOSE 3000
ENV NODE_ENV=production NEXT_TELEMETRY_DISABLED=1 PORT=3000
CMD ["npm", "start"]
