FROM node:20-alpine AS builder

WORKDIR /app

# Copy only package files first for caching
COPY next-dashboard/package*.json ./

RUN npm install

# Copy source and build
COPY next-dashboard/. ./

RUN npm run build

# Final stage
FROM node:20-alpine

WORKDIR /app

ENV NODE_ENV=production
ENV PORT=3000

# Copy built artifacts
COPY --from=builder /app/.next ./.next
COPY --from=builder /app/public ./public
COPY --from=builder /app/node_modules ./node_modules
COPY --from=builder /app/package.json ./package.json

EXPOSE 3000

CMD ["node", ".next/standalone/server.js"]
