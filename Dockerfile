# Stage 1: Build the Next.js app
FROM node:20-alpine AS builder
WORKDIR /app

# Copy only the next-dashboard subdirectory
COPY next-dashboard/package*.json ./
RUN npm install

COPY next-dashboard/. .
RUN npm run build

# Stage 2: Run the Next.js app
FROM node:20-alpine AS runner
WORKDIR /app

ENV NODE_ENV=production

# Copy built output from builder
COPY --from=builder /app/.next ./.next
COPY --from=builder /app/public ./public
COPY --from=builder /app/node_modules ./node_modules
COPY --from=builder /app/package.json ./package.json

EXPOSE 3000
ENV PORT=3000

CMD ["npm", "start"]
