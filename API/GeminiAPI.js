// Importação da biblioteca Google Generative AI
import { GoogleGenerativeAI } from "@google/generative-ai";

// Inicializa o GoogleGenerativeAI com a chave da API definida como variável de ambiente no terminal
const genAI = new GoogleGenerativeAI(process.env.API_KEY);

// Escolha o modelo
const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });

// Define o prompt
const prompt = "Tell me a history about a magic backpack";

// Gera o conteúdo
const result = await model.generateContent(prompt);
console.log(await result.response.text());
