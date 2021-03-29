import hre from 'hardhat';
const { ethers } = hre;

const network = hre.network.name;
let ethscan;

if (network === 'mumbai')
  ethscan = 'https://explorer-mumbai.maticvigil.com/';
else
  ethscan = 'https://explorer-mainnet.maticvigil.com';


const factory = await ethers.getContractFactory("KRE");
const kre = await factory.deploy();
console.log(`Contract  ${ethscan}/address/${kre.address}`);

const tx = (await kre.deployed()).deployTransaction;
console.log(`TX        ${ethscan}/tx/${tx.hash}`);

console.log('KRE deployed!');
console.log(await kre.symbol());
